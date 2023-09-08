 <?php

if($_POST["action"] == "CreatePrintProd")
    {
        
        CreatePrintProd();
    }
    elseif($_POST["action"] == "CreateWooProd")
    {
        CreateWooProd();
    }



function CreatePrintProd() {

    $image = $_POST["imgUrl"];
      
    $sizes = $_POST["sizes"];
    $colors = $_POST["colors"];
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.printful.com/store/products/31803',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS => '',
        CURLOPT_HTTPHEADER => array(
            'X-PF-Store-Id: 10662',
            'Content-Type: application/json',
            'Authorization: Bearer hjFXwskgjO',
            'Cookie: __cf_bm=2k4_7iVqjfVtAeOJm3rikQ9efSXhGMQgxfPM5Ti4dCU-1692602740-0-AbNblKCfaoSs57tjBf3cnHR1lLg5pk4/fM2Wd52t1sBD8x1P//3Esii7ck63VKNIn9k4+x1W+W/RCnwHW7cmoX4=; dsr_setting=%7B%22region%22%3A1%2C%22requirement%22%3Anull%7D'
        ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    $data = json_decode($response, TRUE);
    $id = $data['result']['sync_product']['id'];
    $external_id = $data['result']['sync_product']['external_id'];
    $name = $data['result']['sync_product']['name'];
    $varients = $data['result']['sync_variants'];
    
    $arrayVarient = array();
    
    foreach ($varients as $varientdata) {
        $varientName = $varientdata['name'];
        $varientId = $varientdata['variant_id'];
            
        $arrayVarient[] = array(
            'Size' => $varientName,
            'varient' => $varientId
        );
    }
    
    $sizesColor = array();
    
    foreach ($sizes as $size) {
        foreach ($colors as $color) {
            $sizesColor[] = $color . " / " . $size;
        }
    }
    
    $varientArray = array();
    $getSizeColor = array(); 
    
    foreach ($arrayVarient as $item) {
        foreach ($sizesColor as $pattern) {
            if (strpos($item["Size"], $pattern) !== false) {
                $varientArray[] = $item["varient"];
                $sizeColor = explode(" / ", $pattern); 
                $getSizeColor[] = $sizeColor; 
                break; 
            }
        }
    }




// ----------------Start Api To Create product In PrintFull---------------




$sync_variants = array();

    foreach ($varientArray as $variant) {
        $sync_variants[] = array(
            "variant_id" => $variant,
            "files" => array(
                array(
                    "image" => $image,
                    "url" => $image
                )
            )
        );
    }

$sync_variants_json = json_encode($sync_variants);

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.printful.com/store/products',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '{
        "sync_product": {
            "name": "Test Product",
            "image": "'.$image.'",
            "thumbnail": "'.$image.'",
            "retail_price": "21.00",
            "currency": "USD",
            "files": [
                {
                    "image": "'.$image.'",
                    "url": "'.$image.'"
                }
            ]
        },
        "sync_variants": '.$sync_variants_json.'
    }',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer hl7jFXwskgjO',
        'Content-Type: application/json',
        'Cookie: __cf_bm=WxRnlHBjnu_Nt1pkkHbOqfB7FGP.kSwaiJPjnEyLI50-1692355564-0-AWYHUEvAxSlj4BicRLvjxbJANbacr5Fg/kPq4MnxNN0BMlNZI01sCohAPg/9X9pNR9W7nqREHCNergHBc7z77ts=; dsr_setting=%7B%22region%22%3A1%2C%22requirement%22%3Anull%7D'
    ),
));

$response = curl_exec($curl);
curl_close($curl);
$data = json_decode($response, TRUE);
$id = $data['result']['id'];
// echo"<pre>";
// print_r($data);


/*=========== Start// Get PrintFull Product With Id ========================*/
    
    sleep(15);
    
      $curl = curl_init();
      curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.printful.com/store/products/$id",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'X-PF-Store-Id: 1062',
        'Content-Type: application/json',
        'Authorization: Bearer hOg9ZjFXwskgjO',
        'Cookie: __cf_bm=ZMkbHEA8K3kO2qNV4W7Srce6YTVTLjRZv5_E346ejQs-1691740272-0-AWDX+5n05xrnTLc36DJykRlWwRTJ51jcfFwZDd0CJp8IE0SxtyfsK9kWAmhhsAOOU0BzmRnQVuaqcElLrilpGVI=; dsr_setting=%7B%22region%22%3A1%2C%22requirement%22%3Anull%7D'
      ),
    ));
    
    $response = curl_exec($curl);
    curl_close($curl);
    $data = json_decode($response,TRUE);
    //print_r($data);
    
     $id=$data['result']['sync_product']['id'];
     $external_id=$data['result']['sync_product']['external_id'];
     $name=$data['result']['sync_product']['name'];
     foreach($data['result']['sync_variants'] as $fileData){
           
         foreach($fileData['files'] as $allFileData){  
               
            if($allFileData['type']=="preview"){
           
                $mockURLs[]= $allFileData['preview_url'];
            }
         }
     }
    
     $preview_url = $mockURLs[0];
/*=========== End// Get PrintFull Product With Id ========================*/
     


/*===========Update PrintFull Product Thumbnail Api Start========================*/
    
     
      $curl = curl_init();
      curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.printful.com/store/products/$id",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'PUT',
      CURLOPT_POSTFIELDS =>'{
      "sync_product": {
        "thumbnail": "'.$preview_url.'"
      }
    
    }',
      CURLOPT_HTTPHEADER => array(
        'X-PF-Store-Id: 1052',
        'Content-Type: application/json',
        'Authorization: Bearer hLUMKCJvfn',
        'Cookie: __cf_bm=DJdzvkiBAYE82Z.ZqCQ7KLG1p4llJz0cE1UTj.LUvTc-1692605251-0-AdDJEjUkrEl4YDaCnVRyUWlz7kuiDvN/W6SjwiqQ8MnJjaV+9Mb3VP/HMSOvMa8SZnKg3e5MuVR3xdhvxlpyVzY=; dsr_setting=%7B%22region%22%3A1%2C%22requirement%22%3Anull%7D'
      ),
    ));
    
      $response = curl_exec($curl);
       $data = json_decode($response,TRUE);
    
      $id = $data['result']['id'];
     if(isset($id))
     {
      $arrayreturn = array(
         "id" => $id,
         "name" => $name,
         "externalID" =>$external_id,
         "previewImage" => $preview_url,
         "varcolors" => $colors ,
         "varsizes" => $sizes
         );
        echo json_encode($arrayreturn);
     } 
}




function CreateWooProd(){
        

    $printId=$_POST["printfulId"];
    $name=$_POST["name"];
    $externalID=$_POST["externalID"];
    $preview_url=$_POST["previewImage"];
    $varcolors=$_POST["varcolors"];
    $varsizes=$_POST["varsizes"];

    $description="Made from 100% organic ring-spun cotton, this unisex t-shirt is a total must-have. It's high-quality, super comfy, and best of all—eco-friendly.
                  • 100% organic ring-spun cotton
                  • Fabric weight: 5.3 oz/yd² (180 g/m²)
                  • Single jersey
                  • Medium fit
                  • Set-in sleeves
                  • 1 × 1 rib at collar
                  • Wide double-needle topstitch on the sleeves and bottom hems
                  • Self-fabric neck tape (inside, back of the neck)
                  • Blank product sourced from China or Bangladesh
                  
                  The sizes correspond to a smaller size in the US market, so US customers should order a size up.";
  

    $curl = curl_init();
    $product_data = [
                        "name" =>  $name,
                        "type" => "variable",
                        "regular_price" => "19.99",
                        "sku" => "$printId",
                        "description" => $description,
                        "purchasable" => true,
                        "meta_data" => [
                                        [
                                            "key" => "printfulID",
                                            "value" => $externalID
                                        ]
                                    ],
                        "stock_quantity" => 2200,
                        "images" => [
                                    [
                                        "src" => $preview_url
                                    ],
                                   
                                ],
                        "attributes" => [
                                        [
                                            "name" => "Size",
                                            "position" => 0,
                                            "visible" => true,
                                            "variation" => true,
                                            "options" => $varsizes
                                        ],
                                        [
                                            "name" => "Color",
                                            "position" => 1,
                                            "visible" => true,
                                            "variation" => true,
                                            "options" => $varcolors
                                        ]
                                    ],
                        "categories" => [
                                        [
                                            "id" => "16"
                                        ]
                                    ],
                        "variations" => []
                    ];

    $product_data_json = json_encode($product_data);

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://mai-shirt.de/wp-json/wc/v3/products',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $product_data_json,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Basic Y2tfONDNiMjY3ZTYy'
        ),
        ));


    $response = curl_exec($curl);
    curl_close($curl);
    $response;
    $data = json_decode($response,TRUE);

    foreach ($varsizes as $size) {
        foreach ($varcolors as $color) {
            $variation_data[] = [
                "attributes" => [
                    [
                        "name" => "Size",
                        "option" => $size
                    ],
                    [
                        "name" => "Color",
                        "option" => $color
                    ]
                ],
                "regular_price" => "20.00" // Set your desired price here
            ];
    
    }}
    
    foreach($variation_data as $datavalue){
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => "https://mai-shirt.de/wp-json/wc/v3/products/$id/variations",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($datavalue),
                CURLOPT_HTTPHEADER => array(
                  'Content-Type: application/json',
                  'Authorization: Basic Y2tfOWVj3ZTYy'
                ),
              ));
              
              $response1 = curl_exec($curl);
              curl_close($curl);
              $data2 = json_decode($response1,TRUE);
              $permalink[] = $data2['permalink'];
              
    }
        echo $preview_url= $permalink[0];


}




