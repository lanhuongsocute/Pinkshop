<?php
namespace App\Http\Controllers;

use App\Http\Controllers\HttpClient;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
class KiotController 
{
   
    public function getToken()
    {
        echo '<br/>';
        $client_id ="";
        $client_secret = "";
      
        if(\Session::get('accesstoken') == null)
        {
            try {
                $http = new Client(); //GuzzleHttp\Client
                $response = $http->post(
                    'https://id.kiotviet.vn/connect/token',
                    [
                        'form_params' => [
                            'scopes'=>'PublicApi.Access',
                            'grant_type' => 'client_credentials',
                            'client_id' =>env('KIOT_CLIENT_ID') ,
                            'client_secret' => env('KIOT_CLIEN_SR'),
                            'redirect_uri' => '',
                        ],
                    ]
                );
                $array = $response->getBody()->getContents();
                $json = json_decode($array, true);
                // dd( $json);
                $collection = collect($json);
                $access_token = $collection->get('access_token');
                \Session::put('accesstoken', $access_token );  
            } catch (RequestException $e) {
                return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
            }
        }
        $access_token = \Session::get('accesstoken');
        return $access_token;
    }
    
    public function kiotAddCategory($title,$cat_id,$parentId)
    {
        $send_object['categoryName'] = $title;
        echo $send_object['categoryName'] ;
        // $send_object['parentId'] = 0 ;
        if($parentId!= null)
        {
            $kiot_cat = \App\Models\KiotCat::where('categoryId',$parentId)->first();
            if($kiot_cat == null)
                return;
            $newkiot['parentKiotId'] = $kiot_cat->kiotId;
            $newkiot['parentId'] =$parentId;
            $send_object['parentId'] = $kiot_cat->kiotId;
           
        }
        
        // $params = [
        //                 'body' => json_encode([$send_object])
        // ];
        //  $params = [
        //                 'listCustomers' => $json_sen
        // ];
        if(\Session::get('accesstoken'))
        {
            
            $accesstoken = \Session::get('accesstoken');
        }
        else
        {
            $this->getToken();
            $accesstoken = \Session::get('accesstoken');
        }
        // echo  $accesstoken;
        $client = new ApiClient();
        $url = "https://public.kiotapi.com/categories";
        $accessToken =  $accesstoken;
        $retailer = "tanphatad";
    
        // dd($params );
        $headers = [
            'Content-Type' => 'application/json',
            'Retailer' => $retailer,
            'Authorization'=> 'Bearer '.$accesstoken,
        ];
        // $re = $client->doRequest('POST', $url, $params, $accessToken, $retailer, $headers,'json' );
        // dd($re);
        $client = new Client([
            'headers' => $headers
        ]);
        
        $re  = $client->post( $url,
            ['body' => json_encode(
                 $send_object
            )]
        );
        if($re->getStatusCode() == 200)
        {
            $body_res =json_decode( $re->getBody()->getContents());
            // dd($body_res);
            // $body_res = json_decode( $body_res );
            if ($body_res->data)  
            {
                $data = $body_res->data;
                $newkiot['categoryId'] =$cat_id;
                $newkiot['kiotId']= $data->categoryId ;
                $dateTime = new \DateTime($data->createdDate);
                $newkiot['modifiedDate']= $dateTime->format('Y-m-d H:i:s'); ;
                \App\Models\KiotCat::create($newkiot);
            }
        }
    
       
    }
    public function kiotAddProduct($data)
    {
        
        if(\Session::get('accesstoken'))
        {
            
            $accesstoken = \Session::get('accesstoken');
        }
        else
        {
            $this->getToken();
            $accesstoken = \Session::get('accesstoken');
        }
        // echo  $accesstoken;
        $client = new ApiClient();
        $url = "https://public.kiotapi.com/products";
        $accessToken =  $accesstoken;
        $retailer = "tanphatad";
    
        // dd($params );
        $headers = [
            'Content-Type' => 'application/json',
            'Retailer' => $retailer,
            'Authorization'=> 'Bearer '.$accesstoken,
        ];
        // $re = $client->doRequest('POST', $url, $params, $accessToken, $retailer, $headers,'json' );
        // dd($re);
        $kiot_cat = \App\Models\KiotCat::where('categoryId',$data->cat_id)->first();
        
        if(!$kiot_cat)
            return;
        if($data->type  == 'normal')
            $type = 2;
        else
            $type = 3;
        $str_img = '';
        if($data->photo  != null && $data->photo  != '')
        {
            $images = explode(",",$data->photo );
            foreach ($images as $image)
            {
                $str_img .= '"'.$image.'",';
            }
        }
        
            
        $object = '{
            "name": "'.$data->title .'",  
            "code": "", 
            "barCode": "",  
            "fullName": "'.$data->title .'",  
            "categoryId": '.$kiot_cat->kiotId.',  
            "type": '.$type.',
            "basePrice":'.$data->price_out.',
            "allowsSale": true,  
            "description": "'.$data->description .'",  
            "hasVariants": false,
             
            "conversionValue":1,
             
            "images":  ['.$str_img.'],
        }';
        // echo $object;
        $client = new Client([
            'headers' => $headers
        ]);
        
        $re  = $client->post( $url,
            ['body' => $object]
        );
        if($re->getStatusCode() == 200)
        {
            $body_res =json_decode( $re->getBody()->getContents());
            // dd($body_res);
            // $body_res = json_decode( $body_res );
            if ($body_res)  
            {
                $data_re = $body_res;
                $newkiot['product_id'] =$data->id;
                $newkiot['kiot_product_id']= $data_re->id ;
                $dateTime = new \DateTime($data->createdDate);
                $newkiot['modifiedDate']= $dateTime->format('Y-m-d H:i:s'); ;
                \App\Models\KiotProduct::create($newkiot);
            }
        }
    
       
    }
    public function kiotAddCustomer($data)
    {
        
        if(\Session::get('accesstoken'))
        {
            
            $accesstoken = \Session::get('accesstoken');
        }
        else
        {
            $this->getToken();
            $accesstoken = \Session::get('accesstoken');
        }
        // echo  $accesstoken;
        $client = new ApiClient();
        $url = "https://public.kiotapi.com/customers";
        $accessToken =  $accesstoken;
        $retailer = "tanphatad";
    
        // dd($params );
        $headers = [
            'Content-Type' => 'application/json',
            'Retailer' => $retailer,
            'Authorization'=> 'Bearer '.$accesstoken,
        ];
        // $re = $client->doRequest('POST', $url, $params, $accessToken, $retailer, $headers,'json' );
        // dd($re);
        if($data->taxcode)
            $taxcode = $data->taxcode;
        else
            $taxcode = "";
        $object = '{
            "branchId" : 15691,
            "name": "'.$data->full_name .'",  
            "code": "", 
            "gender": true,  
            "contactNumber": "'.$data->phone.'",  
            "address":" '.$data->address.'",
            "email":"'.$data->email.'",
            "comments": "",  
             
            "taxcode":"'.$taxcode.'",
            "retailerId": 585314,
           
           
        }';
        // echo $object;
        $client = new Client([
            'headers' => $headers
        ]);
        
        $re  = $client->post( $url,
            ['body' => $object]
        );
        if($re->getStatusCode() == 200)
        {
            $body_res =json_decode( $re->getBody()->getContents());
            // dd($body_res);
            if ($body_res)  
            {
                 
                $data_re = $body_res->data;
                $newkiot['customer_id'] =$data->id;
                $newkiot['kiot_customer_id']= $data_re->id  ;
                $dateTime = new \DateTime($data->createdDate);
                $newkiot['modifiedDate']= $dateTime->format('Y-m-d H:i:s'); ;
                \App\Models\KiotCustomer::create($newkiot);
            }
        }
    
       
    }
    public function kiotUpdateCustomer($customer)
    {
        
        $kiot_pro = \App\Models\KiotCustomer::where('customer_id',$customer->id)->first();
        if($kiot_pro == null)
            return;
        // $send_object['id'] = $kiot_cat->kiotId;
      
        if(\Session::get('accesstoken'))
        {
            $accesstoken = \Session::get('accesstoken');
        }
        else
        {
            $this->getToken();
            $accesstoken = \Session::get('accesstoken');
        }
        // echo  $accesstoken;
        $client = new ApiClient();
        $url = "https://public.kiotapi.com/customers/".$kiot_pro->kiot_customer_id ;
        $accessToken =  $accesstoken;
        $retailer = "tanphatad";
      
       
        // dd($params );
        $headers = [
            'Content-Type' => 'application/json',
            'Retailer' => $retailer,
            'Authorization'=> 'Bearer '.$accesstoken,
        ];
       
         
        $object = '{
            
            "branchId" : 15691,
            "name": "'.$customer->full_name .'",  
            
            "gender": true,  
            "contactNumber": "'.$customer->phone.'",  
            "address":" '.$customer->address.'",
            "email":"'.$customer->email.'",
            "comments": "",  
             
            "taxcode":"'.$customer->taxcode.'",
            "retailerId": 585314,
        }';
        $client = new Client([
            'headers' => $headers
        ]);
        try{
            $re  = $client->put( $url,
                [ 
                     
                    'body' =>  $object
                ]
            );
        }
        catch (\Guzzle\Http\Exception\ConnectException $e) {
            //Catch the guzzle connection errors over here.These errors are something 
            // like the connection failed or some other network error
        
            $response = json_encode((string)$e->getResponse()->getBody());
        }
       
        if($re->getStatusCode() == 200)
        {
            $body_res =json_decode( $re->getBody()->getContents());
            if ($body_res )  
            {
                // dd($body_res);
                $data = $body_res->data ;
                $dateTime = new \DateTime($data->modifiedDate);
                $newkiot['modifiedDate']= $dateTime->format('Y-m-d H:i:s'); ;
                $kiot_pro->fill($newkiot)->save();
            }
        }
    }
    public function kiotUpdateProduct($product)
    {
        
        $kiot_pro = \App\Models\KiotProduct::where('product_id',$product->id)->first();
        if($kiot_pro == null)
            return;
        // $send_object['id'] = $kiot_cat->kiotId;
      
        if(\Session::get('accesstoken'))
        {
            $accesstoken = \Session::get('accesstoken');
        }
        else
        {
            $this->getToken();
            $accesstoken = \Session::get('accesstoken');
        }
        // echo  $accesstoken;
        $client = new ApiClient();
        $url = "https://public.kiotapi.com/products/".$kiot_pro->kiot_product_id ;
        $accessToken =  $accesstoken;
        $retailer = "tanphatad";
        $kiot_cat = \App\Models\KiotCat::where('categoryId',$product->cat_id)->first();
       
        // dd($params );
        $headers = [
            'Content-Type' => 'application/json',
            'Retailer' => $retailer,
            'Authorization'=> 'Bearer '.$accesstoken,
        ];
        // $re = $client->doRequest('PUT', $url, $send_object, $accessToken, $retailer, $headers,'json' );
        // dd($re);
        if($product->type  == 'normal')
            $type = 2;
        else
            $type = 3;
        $str_img = '';
        if($product->photo  != null && $product->photo  != '')
        {
            $images = explode(",",$product->photo );
            foreach ($images as $image)
            {
                $str_img .= '"'.$image.'",';
            }
        }
        $object = '{
            
            "name": "'.$product->title .'",  
              
            "categoryId": '.$kiot_cat->kiotId.',  
             
            "basePrice":'.$product->price_out.',
            "allowsSale": true,  
            "description": "'.$product->description .'",  
            "hasVariants": false,
            
            "conversionValue":1,
             
            "images":  ['.$str_img.'],
        }';
        $client = new Client([
            'headers' => $headers
        ]);
        try{
            $re  = $client->put( $url,
                [ 
                     
                    'body' =>  $object
                ]
            );
        }
        catch (\Guzzle\Http\Exception\ConnectException $e) {
            //Catch the guzzle connection errors over here.These errors are something 
            // like the connection failed or some other network error
        
            $response = json_encode((string)$e->getResponse()->getBody());
        }
       
        if($re->getStatusCode() == 200)
        {
            $body_res =json_decode( $re->getBody()->getContents());
            if ($body_res )  
            {
                $data = $body_res ;
                $dateTime = new \DateTime($data->modifiedDate);
                $newkiot['modifiedDate']= $dateTime->format('Y-m-d H:i:s'); ;
                $kiot_pro->fill($newkiot)->save();
            }
        }
    
       
    }
    public function kiotUpdateCategory($title,$cat_id,$parentId)
    {
        $send_object['categoryName'] = $title;
        echo $send_object['categoryName'] ;
        // $send_object['parentId'] = 0 ;
        if($parentId!= null)
        {
            $kiot_cat_parent = \App\Models\KiotCat::where('categoryId',$parentId)->first();
            if($kiot_cat_parent == null)
                return;
            $newkiot['parentKiotId'] = $kiot_cat_parent->kiotId;
            $newkiot['parentId'] =$parentId;
            $send_object['parentId'] = $kiot_cat_parent->kiotId;
           
        }
        $kiot_cat = \App\Models\KiotCat::where('categoryId',$cat_id)->first();
        if($kiot_cat == null)
            return;
        // $send_object['id'] = $kiot_cat->kiotId;
      
        if(\Session::get('accesstoken'))
        {
            $accesstoken = \Session::get('accesstoken');
        }
        else
        {
            $this->getToken();
            $accesstoken = \Session::get('accesstoken');
        }
        // echo  $accesstoken;
        $client = new ApiClient();
        $url = "https://public.kiotapi.com/categories/".$kiot_cat->kiotId;
        $accessToken =  $accesstoken;
        $retailer = "tanphatad";
    
        // dd($params );
        $headers = [
            'Content-Type' => 'application/json',
            'Retailer' => $retailer,
            'Authorization'=> 'Bearer '.$accesstoken,
        ];
        // $re = $client->doRequest('PUT', $url, $send_object, $accessToken, $retailer, $headers,'json' );
        // dd($re);

        $client = new Client([
            'headers' => $headers
        ]);
        try{
            $re  = $client->put( $url,
                [   
                    'body' => json_encode(
                    $send_object
                )]
            );
        }
        catch (\Guzzle\Http\Exception\ConnectException $e) {
            //Catch the guzzle connection errors over here.These errors are something 
            // like the connection failed or some other network error
        
            $response = json_encode((string)$e->getResponse()->getBody());
        }
       
        if($re->getStatusCode() == 200)
        {
            $body_res =json_decode( $re->getBody()->getContents());
            if ($body_res->data)  
            {
                $data = $body_res->data;
                $newkiot['categoryId'] =$cat_id;
                $newkiot['kiotId']= $data->categoryId ;
                $dateTime = new \DateTime($data->modifiedDate);
                $newkiot['modifiedDate']= $dateTime->format('Y-m-d H:i:s'); ;
                $kiot_cat->fill($newkiot)->save();
            }
        }
    
       
    }
    public function KiotBatchProductUpdate()
    {
        if(\Session::get('accesstoken'))
        {
            
            $accesstoken = \Session::get('accesstoken');
        }
        else
        {
            $this->getToken();
            $accesstoken = \Session::get('accesstoken');
        }
        // echo  $accesstoken;
        $client = new ApiClient();
        $url = "https://public.kiotapi.com/products";
        $current_item = 0;
        $page_size = 20;
        $total = 100;
        $accessToken =  $accesstoken;
        $retailer = "tanphatad";
        $load_page = 0;
        $sl_cn = 0;
        while ($current_item  < $total)
        {
            $params = [
                'pageSize'=>$page_size,
                'currentItem' => $current_item,
                'orderBy' => 'id',
                'orderDirection' => 'Asc',
                'includeInventory'=>true,
                'includePricebook'=>true,

            ];
            
            $headers = [
                'Content-Type' => 'application/json',
                'Retailer' => $retailer,
                'Authorization'=> 'Bearer '.$accesstoken,
            ];
            $re = $client->doRequest('GET', $url, $params, $accessToken, $retailer, $headers);
            // dd($re) ;
            $all_data = $re['data'];
            // dd($all_data);
            if($all_data)
            {
                $total  = $all_data['total'];
                $datas = $all_data['data'];
                foreach($datas as $data)
                {
                    set_time_limit(60);
                    $sl_cn+=  \App\Models\KiotProduct::create_kiot_product($data,env('SAVE_IMAGE_DRIVER'));
                }
            }
            else
            {
                $total = 0;
            }
            $current_item += $page_size;
             
            $load_page += 1;
            // if( $load_page  == 3)
                // break;
        }
      
        return $sl_cn;
    }
    public function KiotBatchCategoryUpdate()
    {
        if(\Session::get('accesstoken'))
        {
            
            $accesstoken = \Session::get('accesstoken');
        }
        else
        {
            $this->getToken();
            $accesstoken = \Session::get('accesstoken');
        }
        // echo  $accesstoken;
        $client = new ApiClient();
        $url = "https://public.kiotapi.com/categories";
        $current_item = 0;
        $page_size = 20;
        $total = 100;
        $accessToken =  $accesstoken;
        $retailer = "tanphatad";
        $load_page = 0;
        while ($current_item  < $total)
        {
            $params = [
                'pageSize'=>$page_size,
                'currentItem' => $current_item,
                'orderBy' => 'categoryId',
                'orderDirection' => 'Asc',
                'hierachicalData' => 'true',
            ];
            
            $headers = [
                'Content-Type' => 'application/json',
                'Retailer' => $retailer,
                'Authorization'=> 'Bearer '.$accesstoken,
            ];
            $re = $client->doRequest('GET', $url, $params, $accessToken, $retailer, $headers);
            // dd($re) ;
            $all_data = $re['data'];
            if($all_data)
            {
                $total  = $all_data['total'];
                $datas = $all_data['data'];
                foreach($datas as $data)
                {
                    \App\Models\KiotCat::create_kiot_cat($data,0,0);
                }
            }
            else
            {
                $total = 0;
            }
            $current_item += $page_size;
             
            $load_page += 1;
            // if( $load_page  == 3)
            //     break;
        }
      
        return $total;
      
    }
    public function KiotBatchCustomerUpdate()
    {
        if(\Session::get('accesstoken'))
        {
            
            $accesstoken = \Session::get('accesstoken');
        }
        else
        {
            $this->getToken();
            $accesstoken = \Session::get('accesstoken');
        }
        // echo  $accesstoken;
        $client = new ApiClient();
        $url = "https://public.kiotapi.com/customers";
        $current_item = 0;
        $page_size = 100;
        $total = 100;
        $accessToken =  $accesstoken;
        $retailer = "tanphatad";
        $load_page = 0;
        $sl_cn = 0;
        while ($current_item  < $total)
        {
            $params = [
                'pageSize'=>$page_size,
                'currentItem' => $current_item,
                'orderBy' => 'id',
                'orderDirection' => 'Asc',
                'includeCustomerGroup'=>true,
                'includeTotal'=>true,

            ];
            
            $headers = [
                'Content-Type' => 'application/json',
                'Retailer' => $retailer,
                'Authorization'=> 'Bearer '.$accesstoken,
            ];
            $re = $client->doRequest('GET', $url, $params, $accessToken, $retailer, $headers);
            // dd($re) ;
            $all_data = $re['data'];
            // dd($all_data);
            echo 'add customer';
            if($all_data)
            {
                $total  = $all_data['total'];
                $datas = $all_data['data'];
                foreach($datas as $data)
                {
                    set_time_limit(60);
                    $sl_cn+=  \App\Models\KiotCustomer::create_kiot_customer($data,env('SAVE_IMAGE_DRIVER'));
                }
            }
            else
            {
                $total = 0;
            }
            $current_item += $page_size;
             
            $load_page += 1;
            
            // if( $load_page  == 3)
            //     break;
        }
      
        return $sl_cn;
    }
    public function KiotBatchBranchUpdate()
    {
        if(\Session::get('accesstoken'))
        {
            
            $accesstoken = \Session::get('accesstoken');
        }
        else
        {
            $this->getToken();
            $accesstoken = \Session::get('accesstoken');
        }
        // echo  $accesstoken;
        $client = new ApiClient();
        $url = "https://public.kiotapi.com/branches";
        $current_item = 0;
        $page_size = 20;
        $total = 100;
        $accessToken =  $accesstoken;
        $retailer = "tanphatad";
        $load_page = 0;
        $sl_cn = 0;
        while ($current_item  < $total)
        {
            $params = [
                'pageSize'=>$page_size,
                'currentItem' => $current_item,
                'orderBy' => 'id',
                'orderDirection' => 'Asc',
                
            ];
            
            $headers = [
                'Content-Type' => 'application/json',
                'Retailer' => $retailer,
                'Authorization'=> 'Bearer '.$accesstoken,
            ];
            $re = $client->doRequest('GET', $url, $params, $accessToken, $retailer, $headers);
            // dd($re) ;
            $all_data = $re['data'];
            // dd($all_data);
            
            if($all_data)
            {
                $total  = $all_data['total'];
                $datas = $all_data['data'];
                foreach($datas as $data)
                {
                    set_time_limit(60);
                    $sl_cn+=  \App\Models\KiotBranch::create_kiot_branch($data );
                }
            }
            else
            {
                $total = 0;
            }
            $current_item += $page_size;
             
            $load_page += 1;
            
            // if( $load_page  == 3)
                // break;
        }
      
        return $sl_cn;
    }
    public function KiotBatchUserUpdate()
    {
        if(\Session::get('accesstoken'))
        {
            
            $accesstoken = \Session::get('accesstoken');
        }
        else
        {
            $this->getToken();
            $accesstoken = \Session::get('accesstoken');
        }
        // echo  $accesstoken;
        $client = new ApiClient();
        $url = "https://public.kiotapi.com/users";
        $current_item = 0;
        $page_size = 20;
        $total = 100;
        $accessToken =  $accesstoken;
        $retailer = "tanphatad";
        $load_page = 0;
        $sl_cn = 0;
        while ($current_item  < $total)
        {
            $params = [
                'pageSize'=>$page_size,
                'currentItem' => $current_item,
                'orderBy' => 'id',
                'orderDirection' => 'Asc',
                
            ];
            
            $headers = [
                'Content-Type' => 'application/json',
                'Retailer' => $retailer,
                'Authorization'=> 'Bearer '.$accesstoken,
            ];
            $re = $client->doRequest('GET', $url, $params, $accessToken, $retailer, $headers);
            // dd($re) ;
            $all_data = $re['data'];
            // dd($all_data);
            
            if($all_data)
            {
                $total  = $all_data['total'];
                $datas = $all_data['data'];
                foreach($datas as $data)
                {
                    set_time_limit(60);
                    $sl_cn+=  \App\Models\KiotUser::create_kiot_user($data );
                }
            }
            else
            {
                $total = 0;
            }
            $current_item += $page_size;
             
            $load_page += 1;
            
            // if( $load_page  == 3)
                // break;
        }
      
        return $sl_cn;
    }

    
    public function KiotBatchBankUpdate()
    {
        if(\Session::get('accesstoken'))
        {
            
            $accesstoken = \Session::get('accesstoken');
        }
        else
        {
            $this->getToken();
            $accesstoken = \Session::get('accesstoken');
        }
        // echo  $accesstoken;
        $client = new ApiClient();
        $url = "https://public.kiotapi.com/BankAccounts";
        $current_item = 0;
        $page_size = 20;
        $total = 100;
        $accessToken =  $accesstoken;
        $retailer = "tanphatad";
        $load_page = 0;
        $sl_cn = 0;
        while ($current_item  < $total)
        {
            $params = [
                'pageSize'=>$page_size,
                'currentItem' => $current_item,
                'orderBy' => 'id',
                'orderDirection' => 'Asc',
            ];
            
            $headers = [
                'Content-Type' => 'application/json',
                'Retailer' => $retailer,
                'Authorization'=> 'Bearer '.$accesstoken,
            ];
            $re = $client->doRequest('GET', $url, $params, $accessToken, $retailer, $headers);
            // dd($re) ;
            $all_data = $re['data'];
            // dd($all_data);
            
            if($all_data)
            {
                $total  = $all_data['total'];
                $datas = $all_data['data'];
                foreach($datas as $data)
                {
                    set_time_limit(60);
                    $sl_cn+=  \App\Models\KiotBank::create_kiot_bank($data );
                }
            }
            else
            {
                $total = 0;
            }
            $current_item += $page_size;
             
            $load_page += 1;
            
            // if( $load_page  == 3)
                // break;
        }
      
        return $sl_cn;
    }
    public function KiotBatchCustomerGroupUpdate()
    {
        if(\Session::get('accesstoken'))
        {
            
            $accesstoken = \Session::get('accesstoken');
        }
        else
        {
            $this->getToken();
            $accesstoken = \Session::get('accesstoken');
        }
        // echo  $accesstoken;
        $client = new ApiClient();
        $url = "https://public.kiotapi.com/customers/group";
        $current_item = 0;
        $page_size = 100;
        $total = 100;
        $accessToken =  $accesstoken;
        $retailer = "tanphatad";
        $load_page = 0;
        $sl_cn = 0;
        while ($current_item  < $total)
        {
            $params = [
                'pageSize'=>$page_size,
                'currentItem' => $current_item,
                'orderBy' => 'id',
                'orderDirection' => 'Asc',
            ];
            
            $headers = [
                'Content-Type' => 'application/json',
                'Retailer' => $retailer,
                'Authorization'=> 'Bearer '.$accesstoken,
            ];
            $re = $client->doRequest('GET', $url, $params, $accessToken, $retailer, $headers);
            // dd($re) ;
            $all_data = $re['data'];
            // dd($all_data);
            
            if($all_data)
            {
                $total  = $all_data['total'];
                $datas = $all_data['data'];
                foreach($datas as $data)
                {
                    set_time_limit(60);
                    $sl_cn+=  \App\Models\KiotCustomergroup::create_kiot_bank($data );
                }
            }
            else
            {
                $total = 0;
            }
            $current_item += $page_size;
             
            $load_page += 1;
            
            // if( $load_page  == 3)
                // break;
        }
      
        return $sl_cn;
    }
    public function KiotBatchFlowUpdate()
    {
        if(\Session::get('accesstoken'))
        {
            
            $accesstoken = \Session::get('accesstoken');
        }
        else
        {
            $this->getToken();
            $accesstoken = \Session::get('accesstoken');
        }
        // echo  $accesstoken;
        $client = new ApiClient();
        $url = "https://public.kiotapi.com/cashflow";
        
        $current_item = 0;
        $page_size = 20;
        $total = 100;
        $accessToken =  $accesstoken;
        $retailer = "tanphatad";
        $load_page = 0;
        $sl_cn = 0;
        while ($current_item  < $total)
        {
            $params = [
                'pageSize'=>$page_size,
                'currentItem' => $current_item,
                'includeAccount'=>True,
                'includeBranch'=>True,
                'includeUser'=>True,
                 
               
            ];
            
            $headers = [
                'Content-Type' => 'application/json',
                'Retailer' => $retailer,
                'Authorization'=> 'Bearer '.$accesstoken,
            ];
            $re = $client->doRequest('GET', $url, $params, $accessToken, $retailer, $headers);
            dd($re) ;
        }
    }
    public function KiotBatchWarehouseinUpdate()
    {
        if(\Session::get('accesstoken'))
        {
            
            $accesstoken = \Session::get('accesstoken');
        }
        else
        {
            $this->getToken();
            $accesstoken = \Session::get('accesstoken');
        }
        // echo  $accesstoken;
        $client = new ApiClient();
        $url = "https://public.kiotapi.com/purchaseorders";
        // $url = "https://public.kiotapi.com/suppliers";
        $current_item = 0;
        $page_size = 20;
        $total = 100;
        $accessToken =  $accesstoken;
        $retailer = "tanphatad";
        $load_page = 0;
        $sl_cn = 0;
        while ($current_item  < $total)
        {
            $params = [
                'pageSize'=>$page_size,
                'currentItem' => $current_item,
                'includePayment'=>True,
                'status'=>3,
                'orderBy' => 'id',
                'orderDirection' => 'Desc',
            ];
            
            $headers = [
                'Content-Type' => 'application/json',
                'Retailer' => $retailer,
                'Authorization'=> 'Bearer '.$accesstoken,
            ];
            $re = $client->doRequest('GET', $url, $params, $accessToken, $retailer, $headers);
            // dd($re) ;
            $all_data = $re['data'];
            // dd($all_data);
            
            if($all_data)
            {
                $total  = $all_data['total'];
                $datas = $all_data['data'];
                foreach($datas as $data)
                {
                    set_time_limit(60);
                    $sl_cn+=  \App\Models\KiotWarehousein::create_kiot_warehousein($data );
                }
            }
            else
            {
                $total = 0;
            }
            $current_item += $page_size;
             
            $load_page += 1;
            
            // if( $load_page  == 3)
                // break;
        }
      
        return $sl_cn;
    }
    
    public function KiotBatchWarehouseoutUpdate()
    {
        if(\Session::get('accesstoken'))
        {
            
            $accesstoken = \Session::get('accesstoken');
        }
        else
        {
            $this->getToken();
            $accesstoken = \Session::get('accesstoken');
        }
        // echo  $accesstoken;
        $client = new ApiClient();
        $url = "https://public.kiotapi.com/invoices";
        // $url = "https://public.kiotapi.com/suppliers";
        $current_item = 0;
        $page_size = 20;
        $total = 100;
        $accessToken =  $accesstoken;
        $retailer = "tanphatad";
        $load_page = 0;
        $sl_cn = 0;
        while ($current_item  < $total)
        {
            $params = [
                'pageSize'=>$page_size,
                'currentItem' => $current_item,
                'includePayment'=>True,
                'status'=>1,
                'orderBy' => 'id',
                'orderDirection' => 'Asc',
            ];
            
            $headers = [
                'Content-Type' => 'application/json',
                'Retailer' => $retailer,
                'Authorization'=> 'Bearer '.$accesstoken,
            ];
            $re = $client->doRequest('GET', $url, $params, $accessToken, $retailer, $headers);
            // dd($re) ;
            $all_data = $re['data'];
            // dd($all_data);
            
            if($all_data)
            {
                $total  = $all_data['total'];
                $datas = $all_data['data'];
                foreach($datas as $data)
                {
                    set_time_limit(60);
                    $sl_cn+=  \App\Models\KiotWarehouseout::create_kiot_warehouseout($data );
                }
            }
            else
            {
                $total = 0;
            }
            $current_item += $page_size;
             
            $load_page += 1;
            
            // if( $load_page  == 3)
                // break;
        }
      
        return $sl_cn;
    }
    public function KiotGetCustomer( $id)
    {
        if(\Session::get('accesstoken'))
        {
            
            $accesstoken = \Session::get('accesstoken');
        }
        else
        {
            $this->getToken();
            $accesstoken = \Session::get('accesstoken');
        }
        // echo  $accesstoken;
        $client = new ApiClient();
        $url = "https://public.kiotapi.com/customers/".$id;
        $current_item = 0;
        $page_size = 20;
        $total = 100;
        $accessToken =  $accesstoken;
        $retailer = "tanphatad";
        $load_page = 0;
        $sl_cn = 0;
        
            $params = [
                
                
            ];
            
            $headers = [
                'Content-Type' => 'application/json',
                'Retailer' => $retailer,
                'Authorization'=> 'Bearer '.$accesstoken,
            ];
            $re = $client->doRequest('GET', $url, $params, $accessToken, $retailer, $headers);
            // dd($re) ;
            if($re['status'] == 'error')
                return null;
            else
            {
                $data = $re['data'];
                return $data;
            }
            
            // dd($all_data);
            
           
      
       
    }

}