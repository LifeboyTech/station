<?php namespace Canary\Station\Controllers;

use Input, Response, Config, Session, Medium;
use Canary\Station\Models\Panel as Panel;
use Canary\Station\Models\Image_moo as Image_moo;
use Canary\Station\Models\S3 as S3;
use Canary\Station\Config\StationConfig as StationConfig;
//use Canary\Station\Models\Medium as Medium;
use Illuminate\Filesystem\Filesystem as File;

class StationFileController extends \BaseController {

	private $tmp_dir = './tmp';

	private $mock_width = 445;

	public function __construct()
    {
    	// make the tmp directory where we can access the uploads
        if (!is_dir($this->tmp_dir)){

			mkdir($this->tmp_dir);
		}
    }

    public function crop(){

    	$file				= Input::get('filename');
    	$panel_name			= Input::get('panel_name');
		$element_name		= Input::get('element_name');
		$method				= Input::get('method');
		$coords				= Input::get('coords');
		$size_name			= Input::get('size_name');
		$panel				= new Panel;
		$user_scope			= $panel->user_scope($panel_name, $method);
		$element			= $user_scope['config']['elements'][$element_name];
		$size 				= $element['sizes'][$size_name];
		$app_config			= StationConfig::app();

		// fetch the original // TODO: we need to force all image uploads to save an original version. not an option. make it standard.
		$this->fetch_original($file, $app_config);

		$orig_size			= getimagesize($this->tmp_dir.'/'.$file);
		$orig_width			= $orig_size[0];
		$orig_height		= $orig_size[1];
		$this->mime 		= $orig_size['mime'];
		$mock_width			= $this->mock_width; // this is the width of the jcrop mock. fixed.
		$mock_height		= floor(($mock_width * $orig_height) / $orig_width);
		$mock_orig_ratio	= $orig_width / $mock_width;
		$target_orig_x 		= floor($coords['x'] * $mock_orig_ratio);
		$target_orig_x2 	= floor($coords['x2'] * $mock_orig_ratio);
		$target_orig_y 		= floor($coords['y'] * $mock_orig_ratio);
		$target_orig_y2		= floor($coords['y2'] * $mock_orig_ratio);

		$max_resize			= 99999;
		$specs				= explode('x', $size['size']);
		$x_val				= intval($specs[0]);
		$x_val				= $x_val == 0 ? $max_resize : $x_val;
		$y_val				= intval($specs[1]);
		$y_val				= $y_val == 0 ? $max_resize : $y_val;
		$letterbox_color	= isset($size['letterbox']) && $size['letterbox'] ? $size['letterbox'] : FALSE;

		$image = new Image_moo;
		$image->load($this->tmp_dir.'/'.$file); // TODO: need to set the BG color. not urgent.
		$image->crop($target_orig_x, $target_orig_y, $target_orig_x2, $target_orig_y2);
		$image->set_jpeg_quality(100);
		$image->save($this->tmp_dir.'/'.$file, TRUE);
		$image->clear();

		$image = new Image_moo;
		$image->load($this->tmp_dir.'/'.$file);
		$image->allow_scale_up(TRUE);
		$image->set_jpeg_quality(100);
		$image->resize($x_val, $y_val);
		$image->save($this->tmp_dir.'/_'.$file, TRUE);
		$this->send_to_s3('_'.$file, $size_name, $app_config); // send new file.

		// delete files from tmp directory. we are done with them
		unlink($this->tmp_dir.'/'.$file);
		unlink($this->tmp_dir.'/_'.$file);

		return Response::json(compact('mock_orig_ratio'));
    }

	public function upload()
	{

		if (!Input::hasFile('uploaded_file')) echo json_encode(['success' => FALSE, 'reason' => 'no file uploaded']);

		$file						= Input::file('uploaded_file');
		$original_file_name			= $file->getClientOriginalName();
		$size						= $file->getSize();
		$mime						= $file->getMimeType();
		$this->mime 				= $mime;
		$extension					= $file->getClientOriginalExtension();
		$path 			 			= pathinfo($original_file_name);
		$orig_name_wo_ext 			= $path['filename'];
		$new_file_name				= $orig_name_wo_ext.'_'.date('Y-m-d-H-i-s').'.'.$extension;
		$panel						= new Panel;
		$panel_name					= Input::get('panel_name');
		$parent_panel_name			= Input::get('parent_panel_name');
		$element_name				= Input::get('upload_element_name');
		$method						= Input::get('method');
		$user_scope					= $panel->user_scope($panel_name, $method, $parent_panel_name);
		$element					= $user_scope['config']['elements'][$element_name];
		$app_config					= StationConfig::app();
		$success 					= FALSE;
		$message 					= '';
		
		Input::file('uploaded_file')->move($this->tmp_dir, $new_file_name);

		if ($element['type'] == 'image'){

			$allowed_image_extensions	= ['png', 'gif', 'jpg', 'jpeg', 'PNG', 'GIF', 'JPG', 'JPEG'];
			$bad_image = strpos($mime, 'image') === FALSE || !in_array($extension, $allowed_image_extensions);

			if ($bad_image) return Response::json(['success' => FALSE, 'reason' => 'not a proper image']);

			$allow_upsize	= isset($element['allow_upsize']) && $element['allow_upsize'];
			$all_sizes		= $panel->img_sizes_for($user_scope, $app_config);
			$sizes_needed	= isset($all_sizes[$element_name]) ? $all_sizes[$element_name] : $all_sizes['standard'];
			$manipulations	= $this->manipulate_sizes_and_send_each($new_file_name, $sizes_needed, $app_config, $allow_upsize);
			$success 		= $manipulations['n_sent'] > 0;
			$message 		= $manipulations['n_sent'].' manipulations made and sent to S3';

			// Now that we're all good with it in S3, we insert to media table
			$medium = new Medium;

			$medium->ownertype = 1; // hard set to be owned by userid
			$medium->ownerid = Session::get('user_data.id');
			$medium->filesize = $size;
			$medium->filename = $new_file_name;
			$medium->filetype = 1;	// 1 = image
			$medium->used_at = '["'.$panel_name.'"]';

			$medium->save();

		} else { // file?

			// TODO: deal with non-images here. check for allowed types. then just move and send to S3.
		}

		$response = [

			'success'	=> $success,
			'message'	=> $message,
			'insert_id'	=> isset($medium->id) ? $medium->id : FALSE,
			'file_uri_stub' => 'http://'.$app_config['media_options']['AWS']['bucket'].'.s3.amazonaws.com/',
			'file_uri'	=> isset($manipulations['file_name']) ? 'http://'.$app_config['media_options']['AWS']['bucket'].'.s3.amazonaws.com/'.'station_thumbs_lg/'.$manipulations['file_name'] : FALSE,
			'file_name'	=> isset($manipulations['file_name']) ? $manipulations['file_name'] : FALSE
        ];


        //return Response::json($response); // was erroring with Resource interpreted as Document but transferred with MIME type application/json: "http://willdoo3.localhost/station/file/upload".
        echo json_encode($response);
	}

	private function fetch_original($filename, $app_config){

    	$bucket = $app_config['media_options']['AWS']['bucket'];
    	$ch = curl_init($bucket.".s3.amazonaws.com/original/".$filename);
		$fp = fopen($this->tmp_dir."/".$filename, "w");

		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);

		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
    }

	private function manipulate_sizes_and_send_each($file, $sizes_needed, $app_config, $allow_upsize = TRUE){

		if (count($sizes_needed) == 0) return FALSE;

		$i			= 0;
		$max_resize	= 99999;

		// Setting connection to S3
		//$this->s3 = new S3($app_config['media_options']['AWS']['key'], $app_config['media_options']['AWS']['secret']);

		foreach ($sizes_needed as $directory => $version) {

			if (!isset($version['size']) || $version['size'] == ''){ // no sizing needed, just send original version

				$this->send_to_s3($file, $directory,$app_config, TRUE);
				continue;
			} 

			$specs				= explode('x', $version['size']);
			$x_val				= intval($specs[0]);
			$x_val				= $x_val == 0 ? $max_resize : $x_val;
			$y_val				= intval($specs[1]);
			$y_val				= $y_val == 0 ? $max_resize : $y_val;
			$letterbox_color	= isset($version['letterbox']) && $version['letterbox'] ? $version['letterbox'] : FALSE;
			$method				= ($x_val == $max_resize || $y_val == $max_resize || $letterbox_color) ? 'resize' : 'resize_crop';
			
			if ($x_val == $max_resize && $y_val == $max_resize) continue; // nothing defined. not a well defined spec.

			$image = new Image_moo;
			$image->load($this->tmp_dir.'/'.$file);
			$image->set_background_colour($letterbox_color ?: '#000000');
			$image->set_jpeg_quality(100);
			$image->$method($x_val, $y_val, $letterbox_color ? TRUE : FALSE);
			$image->save($this->tmp_dir.'/_'.$file, TRUE); // save, keep original, prepend new file with underscore.
			$this->send_to_s3('_'.$file, $directory,$app_config); // send new file.

			$i++;
		}
		
		// make station large thumb, send to S3
		$image = new Image_moo;
		$image->load($this->tmp_dir.'/'.$file);
		$image->resize($this->mock_width, $max_resize); // station large thumb spec
		$image->set_jpeg_quality(100);
		$image->save($this->tmp_dir.'/_'.$file, TRUE);
		$this->send_to_s3('_'.$file, 'station_thumbs_lg',$app_config);

		// make station small thumb, send to S3
		$image = new Image_moo;
		$image->load($this->tmp_dir.'/'.$file);
		$image->resize_crop(100, 100); // station large thumb spec
		$image->set_jpeg_quality(100);
		$image->save($this->tmp_dir.'/_'.$file, TRUE);
		$this->send_to_s3('_'.$file, 'station_thumbs_sm',$app_config);
		
		// delete upload from tmp directory. we are done with it
		unlink($this->tmp_dir.'/'.$file);
		unlink($this->tmp_dir.'/_'.$file);

		return array('n_sent' => $i, 'file_name' => $file);
	}

	private function send_to_s3($file, $s3_directory = '',$app_config, $is_orig = FALSE){

		$target = $is_orig ? $s3_directory."/".$file : $s3_directory."/".substr($file,1);
		$this->s3 = new S3($app_config['media_options']['AWS']['key'], $app_config['media_options']['AWS']['secret']);
		$this->s3->putObject(file_get_contents($this->tmp_dir.'/'.$file), $app_config['media_options']['AWS']['bucket'], $target, 'public-read',array(),$this->mime);
		unset($this->s3);
	}

}