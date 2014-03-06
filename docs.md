# Documentation

## Panels

### panel_config

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
