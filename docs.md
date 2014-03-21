# Documentation

## Panels

### panel_config

#### override

##### Definition

To run your own code for that specific method instead of station. You give it the controller + method name. You can use the wildcard **%user_id%** and pass that to the override if you need to. The override **%id%** is also available if you need to pass an id. (Handy when overriding the **U**)

##### Example

```
return [
			'panel_options'	=> [
				'override' 				=> ['L' => 'MessagingController@message_inbox'],
```


#### button_override

##### Definition

Sometimes you don't want to use the default text for a button. You always need the **save** and **save_add** keys. If you don't want a **save_add** button at all, set to 0.

##### Example

```
return [
			'panel_options'	=> [
				'button_override'		=> ['C'=>['save'=>'Send Message','save_add'=>0],
											'U'=>['save'=>'Send Reply','save_add'=>0]],
```

#### triggers

##### Definition

Triggers allow you to have some logic happen before or after a certain action **CRUD**. You specify the controller and method you want to pass the id of the element. The one exception is the pre C trigger, which instead passes the post data, since there is no id yet to pass.

##### Example

```
'panel_options' => [
    'table'                 => 'works', // required
        'single_item_name'      => 'Work',  
            'trigger'               => ['post'=>['C'=>'BillingController@listingFeeOnCreate'],
                                            'pre'=>['D'=>'BillingController@listingFeeOnDelete']]
                                            ],
                                            ```
