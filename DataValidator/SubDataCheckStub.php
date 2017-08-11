<?php {
    $substitutionItemList = array();
    $templateItemList     = array();
    $missingFields        = array();
    
    $apikey   = "e8e6345ff301a92842beebff298541a18ffdbff7";
    $apiroot  = "https://api.sparkpost.com/api/v1/";
    $template = "invoice";
    $recsub   = '{
    "substitution_data": {
        "order_id": "2123",
        "order_status": "https://store-phu7azi.mybigcommerce.com/orderstatus.php",
        "first_name": "John",
        "last_name": "Stone",
        "shipping_street_addr1": "123 Main St Apt #2",
        "shipping_street_addr2": "",
        "shipping_city": "Pleasanton",
        "shipping_state": "California",
        "shipping_zip": "94566",
        "shipping_country": "United States",
        "shipping_phone": "(925) 462-3433",
        "billing_street_addr1": "123 Main St Apt #2",
        "billing_street_addr2": "",
        "billing_city": "Pleasanton",
        "billing_state": "California",
        "billing_zip": "94566",
        "billing_country": "United States",
        "order_specific_comments": " Rider weight = 300kg N = Red // Black either side valve stem",
        "sub_total": "£990.00 GBP",
        "shipping": "",
        "grand_total": "£990.00 GBP",
        "productlist": [{
                "item_name": "Custom Wheel Upgrade: Black Spoke Pack",
                "short_description": "",
                "long_description": "",
                "sku": "CWU-50",
                "quantity": "1",
                "item_price": "£50.00 GBP",
                "item_total": "£50.00 GBP",
                "line_details": [{
                        "description": "Black Spoke Upgrade:",
                        "value": "1"
                    },
                    {
                        "description": "Nipple Choice:",
                        "value": "Red"
                    }
                ]
            },
            {
                "item_name": "Dark Energy DMX550-L wheelset: Fat Boy Rim will D-Light you 50mm 1600g",
                "short_description": "",
                "long_description": "",
                "sku": "CWU-50",
                "quantity": "1",
                "item_price": "£940.00 GBP",
                "item_total": "£940.00 GBP",
                "line_details": [{
                        "description": "Drive:",
                        "value": "Shimano/SRAM"
                    },
                    {
                        "description": "Hub Colour:",
                        "value": "Red"
                    },
                    {
                        "description": "Rider Weight",
                        "value": "Above 85kg"
                    }
                ]
            }
        ]
    }}';
    
    $globalsub = '{"substitution_data" : 
    {
        "company_home_url" : "www.sparkpost.com",
        "company_logo" : "https://db.tt/lRplbEmw",
        "logo_height" : "20",
        "logo_width" : "75",
        "header_color" : "#cc6600",
        "header_box_color" : "#fff4ea",
        "order_generic_comments" : "Many thanks for your phone order! You can view your orders build progress, or modify your contact, password & delivery information by logging in to your account using your email address as your user name.",
        "offers" : "",
        "email_address" : "what@address.com"
    }}';
    
    
    //Test Fields to remove/add 
    // Global
    //"company_name" : "Fast Bikes",
    //"company_url" : "Fast Bikes",
    
    // Recipient
    //"billing_phone": "(925) 462-3433",
    
    include 'SubDataCheckLibrary.php';
    SubDataCheck($apikey, $apiroot, $template, $recsub, $globalsub, $substitutionItemList, $templateItemList, $missingFields, False);
    echo "\n\n-----The following output is data coming from the SubDataCheck function which checks what fields are missing from input substitution data that the template is looking for.";
    echo "\n\nWhich Template Fields are Missing from Subsitution Data and/or may be NULL/Empty?\n";
    foreach ($missingFields as $key => $value) {
        echo "\nKey: " . $key . "\t\tValue: " . $value;
                }
    
    echo "\n\nWhat Template Fields did we find and was there content?\n";
    foreach ($templateItemList as $key => $value) {
        echo "\nKey: " . $key . "\t\tValue: " . $value;
                }
    
    
    echo "\n\nWhat Substitution Fields did we find and was there content?\n";
    foreach ($substitutionItemList as $key => $value) {
        echo "\nKey: " . $key . "\t\tValue: " . $value;
                }
    
    
    $templateItemList = array();
    BuildTemplateFields($apikey, $apiroot, $template, $templateItemList, 'fieldoutput.txt');
    echo "\n\n-----The following output is data coming from the BuildTemplateFields function which produces a list of fields the template is looking for";
    echo "\n\nWhat Template Fields did we find?\n";
    foreach ($templateItemList as $key => $value) {
        echo "\nKey: " . $key . "\t\tValue: " . $value;
                }
    
    $results = isJson($recsub);
    echo "\n\n-----The following output is a sample coming from the function 'isJson' that checks that can check if the passed string is a proper Json structure\n";
    echo "which is needed in both 'SubDataCheck' and 'BuildTemplateFields'";
    echo "\n\nResults recsub isJson: " . $results . "\n\n";
    
    echo "\n\n-----This is what the function 'SubDataCheckLibraryEndPoints' produces.  Which in essence is a -h or /h for the library\n\n";
    SubDataCheckLibraryEndPoints();
}
?>
