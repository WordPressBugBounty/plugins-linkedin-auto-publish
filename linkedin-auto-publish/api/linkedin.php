<?php
class LNAPOAuth2{

    protected $access_token;
    protected $access_token_url;
    protected $authorize_url;
    protected $access_token_name;
    public $error;

    function __construct($access_token=''){
        $this->access_token = $access_token;
        $this->error = "";
        $this->access_token_name='access_token';
    }

    public function getAuthorizeUrl($client_id,$redirect_url, $additional_args=array() ){
        $auth_link = $this->authorize_url.
                    "?response_type=code".
                    "&client_id=".$client_id.
                    "&redirect_uri=".urlencode($redirect_url);
        foreach($additional_args as $k=>$v){
            $auth_link.='&'.$k.'='.urlencode($v);
        }
        return $auth_link;
    }


    public function getAccessToken($client_id="", $secret="", $redirect_url="", $code = ""){
        if($code==""){
            $code = isset($_REQUEST['code'])?$_REQUEST['code']:"";
        }
        $params=array();
        $params['url'] = $this->access_token_url;
        $params['method']='post';
        $params['args']=array(  'code'=>$code,
                                'client_id'=>$client_id,
                                'redirect_uri'=>$redirect_url,
                                'client_secret'=>$secret,
                                'grant_type'=>'authorization_code');
        $result = $this->makeRequest($params);
        return $result;
    }

    protected function makeRequest($params=array()){
        $this->error = '';
        $method=isset($params['method'])?$params['method']:'get';
        $headers = isset($params['headers'])?$params['headers']:array();
        $args = isset($params['args'])?$params['args']:'';
        $url = $params['url'];

        $url.='?';
       /* if($this->access_token){////////////////////commented for v2 edge compatibility
            $url .= $this->access_token_name.'='.$this->access_token;
        }*/

        if($method=='get'){
            $url.='&'.$this->preparePostFields($args);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if($method=='post'){
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->preparePostFields($args));
        }elseif($method=='delete'){
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        }elseif($method=='put'){
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        }
        if(is_array($headers) && !empty($headers)){
            $headers_arr=array();
            foreach($headers as $k=>$v){
                $headers_arr[]=$k.': '.$v;
            }
            curl_setopt($ch,CURLOPT_HTTPHEADER,$headers_arr);
        }
        $sslverify=(get_option('xyz_lnap_peer_verification')=='1') ? TRUE : FALSE;
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,$sslverify);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    protected function makeRequest_ln_header($params=array()){
        $this->error = '';
        $method=isset($params['method'])?$params['method']:'get';
        $headers = isset($params['headers'])?$params['headers']:array();
        $args = isset($params['args'])?$params['args']:'';
        $url = $params['url'];
        $url.='?';
       /* if($this->access_token){////////////////////commented for v2 edge compatibility
            $url .= $this->access_token_name.'='.$this->access_token;
        }*/
        if($method=='get'){
            $url.='&'.$this->preparePostFields($args);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER,true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        if($method=='post'){
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->preparePostFields($args));
        }elseif($method=='delete'){
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        }elseif($method=='put'){
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        }
        if(is_array($headers) && !empty($headers)){
            $headers_arr=array();
            foreach($headers as $k=>$v){
                $headers_arr[]=$k.': '.$v;
            }
            curl_setopt($ch,CURLOPT_HTTPHEADER,$headers_arr);
        }
        $sslverify=(get_option('xyz_lnap_peer_verification')=='1') ? TRUE : FALSE;
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,$sslverify);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    protected function makeRequestToAssetAPI($params=array()){
    	$this->error = '';
    	$method=isset($params['method'])?$params['method']:'get';
    	$headers = isset($params['headers'])?$params['headers']:array();
    	$args = isset($params['args'])?$params['args']:'';
    	$url = $params['url'];
    	//     	$url.='?'; do not add ? at post request to asset api
    	/* if($this->access_token){////////////////////commented for v2 edge compatibility
    	 $url .= $this->access_token_name.'='.$this->access_token;
    	 }*/
    	if($method=='get'){
    		$url.='&'.$this->preparePostFields($args);
    	}
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    	if($method=='post'){
    		curl_setopt($ch, CURLOPT_POST, TRUE);
    		if (isset($params['smap']))
    		{
    			$url.='?';
    			curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
    		}
    		else
    			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->preparePostFields($args));
    	}elseif($method=='delete'){
    		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    	}elseif($method=='put'){
    		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    	}
    	if(is_array($headers) && !empty($headers)){
    		$headers_arr=array();
    		foreach($headers as $k=>$v){
    			$headers_arr[]=$k.': '.$v;
    		}
    		curl_setopt($ch,CURLOPT_HTTPHEADER,$headers_arr);
    	}
    	$sslverify=(get_option('xyz_lnap_peer_verification')=='1') ? TRUE : FALSE;
    	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,$sslverify);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    protected function preparePostFields($array) {
        if(is_array($array)){
            $params = array();
            foreach ($array as $key => $value) {
                $params[] = $key . '=' . urlencode($value);
            }
            return implode('&', $params);
        }else{
            return $array;
        }
    }

}




class LNAPLinkedInOAuth2 extends LNAPOAuth2 {

	public function __construct($access_token=''){
		$this->access_token_url = "https://www.linkedin.com/oauth2/accessToken";
		$this->authorize_url = "https://www.linkedin.com/oauth2/authorization";
		parent::__construct($access_token);
		$this->access_token_name='oauth2_access_token';
	}

	public function getAuthorizeUrl($client_id,$redirect_url,$scope=''){
		$additional_args = array();
		if($scope!=''){
			if(is_array($scope)){
				$additional_args['scope']=implode(" ",$scope);
				$additional_args['scope'] = $additional_args['scope'];
			}else{
				$additional_args['scope'] = $scope;
			}
		}
		$additional_args['state'] = md5(time());
		return parent::getAuthorizeUrl($client_id,$redirect_url,$additional_args);
	}

	public function getAccessToken($client_id="", $secret="", $redirect_url="", $code = ""){
		$result = parent::getAccessToken($client_id, $secret, $redirect_url, $code);
		$result = json_decode($result,true);
		if(isset($result['error'])){
			$this->error = $result['error'].' '.$result['error_description'];
			return false;
		}else{
			$this->access_token = $result['access_token'];
			return $result;
		}
	}

	public function getProfile(){
		$params=array();
		$params['headers']['Authorization']='Bearer '.$this->access_token;
		$params['headers']['Connection']='Keep-Alive';

		$params['headers']['X-RestLi-Protocol-Version']='2.0.0';
		$result =  $this->makeRequest($params);


		$this->error = '';
		$method=isset($params['method'])?$params['method']:'get';
		$headers = isset($params['headers'])?$params['headers']:array();
		$args = isset($params['args'])?$params['args']:'';
		$params['url'] = "https://api.linkedin.com/v2/me";
		$params['method']='get';
		$params['args']['format']='json';
		$url = $params['url'];
		$url.='?';
		if($this->access_token){
			$url .'oauth2_access_token='.$this->access_token;
		}
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}

	public function getUserProfile($user_id){
		$params=array();
		$fields = array(
			'current-status',
			'id',
			'picture-url',
			'first-name',
			'last-name',
			'public-profile-url',
			'num-connections',
		);
		$request = implode(',',$fields);
		$params['url'] = "https://api.linkedin.com/v2/me/".$user_id.":({$request})";
		$params['method']='get';
		$params['args']['format']='json';
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}

	public function getConnections(){
		$params=array();
		$params['url'] = "https://api.linkedin.com/v2/me/~/connections";
		$params['method']='get';
		$params['args']['format']='json';
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}

	public function getGroups(){
		$fields = array(
			'group:(id,name)',
			'membership-state',
			'show-group-logo-in-profile',
			'allow-messages-from-members',
			'email-digest-frequency',
			'email-announcements-from-managers',
			'email-for-every-new-post'
		);
		$request = implode(',',$fields);
		$params['url'] = "https://api.linkedin.com/v2/me/~/group-memberships:({$request})";
		$params['method']='get';
		$params['args']['format']='json';
		$params['args']['count']=200;
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}

	public function getGroup($group_id=""){
		if(!$group_id) return false;
		$fields = array(
			'id',
			'small-logo-url',
			'large-logo-url',
			'name',
			'short-description',
			'description',
			'site-group-url',
			'num-members'
		);
		$request = implode(',',$fields);
		$params['url'] = "https://api.linkedin.com/v2/groups/".$group_id.":({$request})";
		$params['method']='get';
		$params['args']['format']='json';
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}

	public function getCompanies(){
		$fields = array(
			'id',
			'name'
		);
		$request = implode(',',$fields);
		$params['url'] = "https://api.linkedin.com/v2/me/~:(first-name,positions:(company:({$request})))";
		$params['method']='get';
		$params['args']['format']='json';
		$params['args']['count']=100;
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}

	public function getCompany($company_id=""){
		if(!$company_id)return false;
		$fields = array(
			'id',
			'name',
			'website-url',
			'square-logo-url',
			'logo-url',
			'blog-rss-url',
			'description',
			'num-followers'
		);
		$request = implode(',',$fields);
		$params['url'] = "https://api.linkedin.com/v2/companies/".$company_id.":({$request})";
		$params['method']='get';
		$params['args']['format']='json';
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}

	public function getAdminCompanies(){
	/*	$fields = array(
			'id',
            'name'
		);
		$request = implode(',',$fields);*/

		$params['url'] = "https://api.linkedin.com/v2/organizationalEntityAcls?q=roleAssignee&role=ADMINISTRATOR&projection=(elements*(*,roleAssignee~(localizedFirstName,%20localizedLastName),%20organizationalTarget~(localizedName)))";
		$params['method']='get';
		$params['headers']['Authorization']='Bearer '.$this->access_token;
		$params['headers']['Content-Type']='application/json';
		$params['headers']['x-li-format']='json';
		$params['headers']['X-Restli-Protocol-Version']='2.0.0';
		$params['headers']['Connection']='Keep-Alive';
		//print_r($params);die;
// 		$params['args']['format']='json';
// 		$params['args']['count']=100;
// 		$params['args']['is-company-admin']='true';
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}

	public function getFollowedCompanies(){
		$fields = array(
			'id',
			'name'
		);
		$request = implode(',',$fields);
		$params['url'] = "https://api.linkedin.com/v2/me/~/following/companies:({$request})";
		$params['method']='get';
		$params['args']['format']='json';
		$params['args']['count']=200;
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}

	public function getStatuses($self = false, $start=0,$count = 20){
		$params['url'] = "https://api.linkedin.com/v2/me/~/network/updates";
		$params['method']='get';
		$params['args']['format']='json';
		if($start != 0 )$params['args']['start']=$start;
		if($count != 0 )$params['args']['count']=$count;
		if($self){
			$params['args']['scope']='self';
		}
		$params['args']['type']='SHAR';
		$params['args']['order']='recency';
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}

	public function getUserStatuses($user_id, $self = true, $start=0,$count = 20){
		$params['url'] = "https://api.linkedin.com/v2/me/id=".$user_id."/network/updates";
		$params['method']='get';
		$params['args']['format']='json';
		if($start != 0 )$params['args']['start']=$start;
		if($count != 0 )$params['args']['count']=$count;
		if($self){
		$params['args']['scope']='self';
		}
		$params['args']['type']='SHAR';
		$params['args']['order']='recency';
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}

	public function getGroupPosts($group_id, $start=0,$count = 20, $order="", $category="",$role=""){
		$fields = array(
			'id',
			'creator:(id,first-name,last-name,picture-url,headline)',
			'title',
			'summary',
			'likes',
			'comments',
			'site-group-post-url',
			'creation-timestamp',
			'attachment:(image-url,content-domain,content-url,title,summary)',
			'relation-to-viewer'
		);
		$request = implode(',',$fields);
		if($role !=""){
			$params['url'] = "https://api.linkedin.com/v2/me/~/group-memberships/".$group_id."/posts:({$request})";
		}else{
			$params['url'] = "https://api.linkedin.com/v2/groups/".$group_id."/posts:({$request})";
		}
		$params['method']='get';
		$params['args']['format']='json';
		if($start != 0 )$params['args']['start']=$start;
		if($count != 0 )$params['args']['count']=$count;
		if($order != '' )$params['args']['order']=$order;
		if($category != '' )$params['args']['category']=$category;
		if($role != '' )$params['args']['role']=$role;
		$params['args']['ts']=time();
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}

	public function getCompanyUpdates($company_id, $start=0,$count = 20){
		if(!$company_id)return false;
		$params['url'] = "https://api.linkedin.com/v2/companies/".$company_id."/updates";
		$params['method']='get';
		$params['args']['format']='json';
		if($start != 0 )$params['args']['start']=$start;
		if($count != 0 )$params['args']['count']=$count;
		$params['args']['order']='recency';
		$params['args']['ts']=time();
		$params['args']['event-type']='status-update';
		$params['args']['twitter-post']='false';
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}
	public function getImagePostResponses($args=array()){
		$params['url'] = "https://api.linkedin.com/rest/images?action=initializeUpload";
		$params['method']='post';
		$params['headers']['Authorization']='Bearer '.$this->access_token;
		$params['headers']['Content-Type']='application/json';//application/binary
    $params['headers']['Linkedin-Version']='202502';
		$params['headers']['X-Restli-Protocol-Version']='2.0.0';
		$params['headers']['Connection']='Keep-Alive';
		$params['args']=json_encode($args,JSON_UNESCAPED_SLASHES);
		$result =  $this->makeRequestToAssetAPI($params);
		return json_decode($result,true);
	}
  public function getImagePostResponses1($args=array()){
		$params['url'] = "https://api.linkedin.com/v2/assets?action=registerUpload";
		$params['method']='post';
		$params['headers']['Authorization']='Bearer '.$this->access_token;
		$params['headers']['Content-Type']='application/json';//application/binary
		$params['headers']['X-Restli-Protocol-Version']='2.0.0';
		$params['headers']['Connection']='Keep-Alive';
		$params['args']=json_encode($args);
		$result =  $this->makeRequestToAssetAPI($params);
		return json_decode($result,true);
	}
	public function getUploadUrlResponses($uploadUrl,$image,$args=array())
	{
		$headers = array();$sslverify=(get_option('xyz_lnap_peer_verification')=='1') ? TRUE : FALSE;
          $response = wp_remote_get($image,$sslverify);

          if (is_array($response) && !is_wp_error($response)) {
            //update_option('lnap_success',1);
            $headers[] = 'Authorization: Bearer '.$this->access_token;
            $headers[] = 'X-Restli-Protocol-Version: 2.0.0';
            $headers[] = 'Content-Type: application/octet-stream';
            $ch = curl_init($uploadUrl);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,$sslverify);
            curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
              $image_data = wp_remote_retrieve_body($response);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $image_data);
          		$response = curl_exec($ch);
          		curl_close($ch);
          		return json_decode($response,true);
          }
          else {
              $response = is_wp_error($response) ? $response->get_error_message() : 'An unknown error occurred.';
              return json_decode($response,true);
          }
	}
	public function check_status_linkedin_asset($url){
		$params['url'] = $url;
		$params['method']='get';
		$params['headers']['Content-Type']='application/json';
		$params['headers']['x-li-format']='json';
		$params['headers']['Connection']='Keep-Alive';
    $params['headers']['Linkedin-Version']='202502';
		$params['headers']['X-RestLi-Protocol-Version']='2.0.0';
		$this->error = '';
		$method=isset($params['method'])?$params['method']:'get';
		$headers = isset($params['headers'])?$params['headers']:array();
		$args = isset($params['args'])?$params['args']:'';
		$url = $params['url'];
		$url.='?oauth2_access_token='.$this->access_token;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		if($method=='post'){
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->preparePostFields($args));
		}elseif($method=='delete'){
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		}elseif($method=='put'){
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		}
		if(is_array($headers) && !empty($headers)){
			$headers_arr=array();
			foreach($headers as $k=>$v){
				$headers_arr[]=$k.': '.$v;
			}
			curl_setopt($ch,CURLOPT_HTTPHEADER,$headers_arr);
		}
		$result = curl_exec($ch);
		curl_close($ch);
		return json_decode($result,true);
	}
	//returns as ARRAY, if chaning to object change in getGroupPostResponses
	protected function getPostMeta($post_id){
		$fields = array(
			'id',
			'site-group-post-url',
			'creation-timestamp'
		);
		$request = implode(',',$fields);
		$params['url'] = "https://api.linkedin.com/v2/posts/".$post_id.":({$request})";
		$params['method']='get';
		$params['args']['format']='json';
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}

	public function getGroupPostResponses($post_id, $start = 0){
		 $fields = array(
			'id',
			'text',
			'creator:(id,first-name,last-name,picture-url)',
			'creation-timestamp'
		);
		$post_info = $this->getPostMeta($post_id);
		$request = implode(',',$fields);
		$params['url'] = "https://api.linkedin.com/v2/posts/".$post_id."/comments:({$request})";
		$params['method']='get';
		$params['args']['format']='json';
		$params['args']['count']=500;
		if($start != 0 )$params['args']['start']=$start;
		$params['args']['order']='recency';
		$content_return =  $this->makeRequest($params);
		$content_return = json_decode($content_return,true);
		$content_return['siteGroupPostUrl'] = isset($post_info['siteGroupPostUrl']) ? $post_info['siteGroupPostUrl'] : '';
		return $content_return;
	}

	public function getNetworkPostResponses($update_key){
		$params['url'] = "https://api.linkedin.com/v2/me/~/network/updates/key=".$update_key."/update-comments";
		$params['method']='get';
		$params['args']['format']='json';
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}

	public function getCompanyUpdateResponses($company_id,$update_id){
		$params['url'] = "https://api.linkedin.com/v2/companies/".$company_id."/updates/key=".$update_id."/update-comments";
		$params['method']='get';
		$params['args']['format']='json';
		$params['args']['event-type']='status-update';
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}

	public function shareStatus($args=array()){
		$params['url'] = 'https://api.linkedin.com/rest/posts';
		$params['method']='post';
		$params['headers']['Authorization']='Bearer '.$this->access_token;
		$params['headers']['Content-Type']='application/json';
		$params['headers']['x-li-format']='json';
    $params['headers']['Linkedin-Version']='202502';
		$params['headers']['X-Restli-Protocol-Version']='2.0.0';
		$params['headers']['Connection']='Keep-Alive';
		$params['args']=json_encode($args);
		$result =  $this->makeRequest_ln_header($params);
    return $result;
		// return json_decode($result,true);
			}
      // public function shareStatus1($args=array()){
    	// 	$params['url'] = 'https://api.linkedin.com/v2/ugcPosts';
    	// 	$params['method']='post';
    	// 	$params['headers']['Authorization']='Bearer '.$this->access_token;
    	// 	$params['headers']['Content-Type']='application/json';
    	// 	$params['headers']['x-li-format']='json';
    	// 	$params['headers']['X-Restli-Protocol-Version']='2.0.0';
    	// 	$params['headers']['Connection']='Keep-Alive';
    	// 	$params['args']=json_encode($args);
    	// 	$result =  $this->makeRequest($params);
    	// 	return json_decode($result,true);
    	// 		}
	public function xyz_lnap_fetch_user_data($user_data_endpoint_url){
		//$params['url'] = 'https://api.linkedin.com/v2/me';
    $params['url'] = $user_data_endpoint_url;
		$params['method']='get';
		$params['headers']['Authorization']='Bearer '.$this->access_token;
		$params['headers']['Content-Type']='application/json';
		$params['headers']['x-li-format']='json';
		$params['headers']['Connection']='Keep-Alive';
		$params['headers']['X-RestLi-Protocol-Version']='2.0.0';
		$result =  $this->makeRequest($params);
		$this->error = '';
		$method=isset($params['method'])?$params['method']:'get';
		$headers = isset($params['headers'])?$params['headers']:array();
		$args = isset($params['args'])?$params['args']:'';
		$url = $params['url'];
		$url.='?';
		if($this->access_token){
			$url .'oauth2_access_token='.$this->access_token;
			}

		if($method=='get'){
			$url.='&'.$this->preparePostFields($args);
			}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		if($method=='post'){
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->preparePostFields($args));
		}elseif($method=='delete'){
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		}elseif($method=='put'){
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			}
		if(is_array($headers) && !empty($headers)){
			$headers_arr=array();
			foreach($headers as $k=>$v){
				$headers_arr[]=$k.': '.$v;
		}
			curl_setopt($ch,CURLOPT_HTTPHEADER,$headers_arr);
		}

		$result = curl_exec($ch);
		curl_close($ch);
		return json_decode($result,true);
	}

	public function postToGroup($group_id,$title,$message,$content=array()){
		$params['url'] = 'https://api.linkedin.com/v2/groups/'.$group_id.'/posts';
		$params['method']='post';
		$params['headers']['Content-Type']='application/json';
		$params['headers']['x-li-format']='json';
		$json = array('title'=>$title,'summary'=>$message);

		if(is_array($content) AND count($content)>0) {
			// If the content of the post is specified (e.g., a link to a website), add it here
			$json['content'] = array();
			if(isset($content['content']['title'])){
				$json['content']['title'] = $content['content']['title'];
			}
			if(isset($content['content']['submitted-url'])){
				$json['content']['submitted-url'] = $content['content']['submitted-url'];
			}
			if(isset($content['content']['submitted-image-url'])){
				$json['content']['submitted-image-url'] = $content['content']['submitted-image-url'];
			}
			if(isset($content['content']['description'])){
				$json['content']['description'] = $content['content']['description'];
			}
		}
		$params['args']=json_encode($json);
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}


	public function postToCompany($company_id,$content=array()){
		$params['url'] = 'https://api.linkedin.com/v2/ugcPosts';
		$params['method']='post';
		$params['headers']['Authorization']='Bearer '.$this->access_token;
		$params['headers']['Content-Type']='application/json';
		$params['headers']['x-li-format']='json';
		$params['headers']['X-Restli-Protocol-Version']='2.0.0';

		$params['headers']['Connection']='Keep-Alive';
		$params['args']=json_encode($content);
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}

	public function deleteFromGroup($post_id){
		$params['url'] = 'https://api.linkedin.com/v2/posts/'.$post_id;
		$params['method']='delete';
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}

	public function commentToGroupPost($post_id,$response_text){
		$params['url'] = 'https://api.linkedin.com/v2/posts/'.$post_id.'/comments';
		$params['method']='post';
		$params['headers']['Content-Type']='application/json';
		$params['headers']['x-li-format']='json';
		$json = array('text'=>$response_text);
		$params['args']=json_encode($json);
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}

	public function commentToNetworkPost($post_id,$response_text){
		$params['url'] = 'https://api.linkedin.com/v2/me/~/network/updates/key='.$post_id.'/update-comments';
		$params['method']='post';
		$params['headers']['Content-Type']='application/json';
		$params['headers']['x-li-format']='json';
		$json = array('comment'=>$response_text);
		$params['args']=json_encode($json);
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}
}


/**
 * This is wrapper class file which will call LinkedIn API functions and return result to the controller.
 */
class LNAPLinkedIn
{

private $api_key='';
private $api_secret='';
private $api_callback='';
private $api_acc_token='';

public function __construct($api_key,$api_secret,$api_callback='')
	{
	$this->api_key=$api_key;
	$this->api_secret=$api_secret;
	$this->api_callback=$api_callback;

	}

    /**
     * Function to get LinkedIn Authorize URL and access token
    */
    function fnLinkedInConnect()
    {
        # Object of class
        $ObjLinkedIn = new LNAPLinkedInOAuth2();
        $strApiKey = $this->api_key;
        $strSecreteKey = $this->api_secret;

        //put here your redirect url
        $strRedirect_url = $this->api_callback;

        $strCode = isset($_REQUEST['code']) ? $_REQUEST['code'] : '';

        if ($strCode == "") {

            try {
                # Get LinkedIn Authorize URL
                #If the user authorizes your application they will be redirected to the redirect_uri that you specified in your request .
                $strGetAuthUrl = $ObjLinkedIn->getAuthorizeUrl($strApiKey, $strRedirect_url);
            } catch (Exception $e) {

            }
            header("Location: ".$strGetAuthUrl);
            exit;
        }

        # Get LinkedIn Access Token
        /**
         * Access token is unique to a user and an API Key. You need access tokens in order to make API calls to LinkedIn on behalf of the user who authorized your application.
         * The value of parameter expires_in is the number of seconds from now that this access_token will expire in (5184000 seconds is 60 days).
         * You should have a mechanism in your code to refresh the tokens before they expire in order to continue using the same access tokens.
         */
        $arrAccess_token = $ObjLinkedIn->getAccessToken($strApiKey, $strSecreteKey, $strRedirect_url, $strCode);
        $strAccess_token = $arrAccess_token["access_token"];

		$this->api_acc_token=$strAccess_token;
    }

    /**
     * To Get List of LinkedIn Company Pages.
     */
    function fnGetLinkedCompanyPages()
    {
        $strAccess_token = $this->api_acc_token;

        # Object of class
        $ObjLinkedin = new LNAPLinkedInOAuth2($strAccess_token);

        # Get List of company pages
        try {
            $arrAdminCompany = $ObjLinkedin->getAdminCompanies();
        } catch (Exception $e) {

        }

        $arrAdminCompanyValue = $arrAdminCompany["values"];
        $intTotalCount = count($arrAdminCompany["_total"]);

        $arrLinkedInPages = array();
        $intCount = 0;
        if (is_array($arrAdminCompanyValue) && count($arrAdminCompanyValue) > 0) {
            foreach ($arrAdminCompanyValue as $arrAdminCompanyInfo) {
                $intFlag = 0;

                $arrLinkedInPages[$intCount]["id"] = (int) $arrAdminCompanyInfo["id"];
                $arrLinkedInPages[$intCount]["name"] = stripslashes($arrAdminCompanyInfo["name"]);
            }
        }
        return $arrLinkedInPages;
    }

    /**
     * To Get List of LinkedIn User Profiles.
     */
    function fnGetLinkedUserProfile()
    {
        $strAccess_token = $this->api_acc_token;
        $intUserId = "USER ID";

        if ($intUserId > 0) {
            # Object of class
            $ObjLinkedin = new LNAPLinkedInOAuth2($strAccess_token);

            # Get List of company pages
            try {
                $arrLinkedInProfile = $ObjLinkedin->getUserProfile($intUserId);
            } catch (Exception $e) {

            }
            return $arrLinkedInProfile;
        }
    }

    /**
     * To Get List of LinkedIn Company Information.
     */
    function fnGetLinkedCompanyInformation()
    {
        $strAccess_token = $this->api_acc_token;
        $intCompanyId = "COMPANY ID";

        if ($intCompanyId > 0) {
            # Object of class
            $ObjLinkedin = new LNAPLinkedInOAuth2($strAccess_token);

            # Get List of company pages
            try {
                $arrLinkedInCompanyInformation = $ObjLinkedin->getCompany($intCompanyId);
            } catch (Exception $e) {

            }
            return $arrLinkedInCompanyInformation;
        }
    }

    /**
     * To get LinkedIn company updates
     */
    function fnGetLinkedInCompanyUpdates()
    {
        $strAccess_token = $this->api_acc_token;
        $intCompanyId = "COMPANY ID";

        if ($intCompanyId > 0) {

            $intStart = 0;
            $intCount = 10;
            $intPage = 1;
            if ($intPage) {
                $intStart = ($intPage - 1) * 10;
            }
            $ObjLinkedin = new LNAPLinkedInOAuth2($strAccess_token);

            try {
                $arrCompanyUpdate = $ObjLinkedin->getCompanyUpdates($intCompanyId, $intStart, $intCount);
            } catch (Exception $e) {

            }
        }
    }

    /**
     * To get LinkedIn user profile updates
     */
    function fnGetLinkedInUserProfileUpdates()
    {
        $strAccess_token = $this->api_acc_token;
        $intUserId = "USER ID";

        if ($intUserId > 0) {

            $intStart = 0;
            $intCount = 10;
            $intPage = 1;
            if ($intPage) {
                $intStart = ($intPage - 1) * 10;
            }
            $ObjLinkedin = new LNAPLinkedInOAuth2($strAccess_token);

            try {
                $arrUserProfileUpdates = $ObjLinkedin->getUserStatuses($intUserId, true, $intStart, $intCount);
            } catch (Exception $e) {

            }
        }
    }


    /**
     * Function to Send status Message on LinkedIn company Pages
     */
    function fnPostMessage()
    {
        $strAccess_token = $this->api_acc_token;
        $intCompanyPageId = "COMPANY ID";
        $strStatusMessage = "STATUS MESSAGE";

        $ObjLinkedin = new LNAPLinkedInOAuth2($strAccess_token);

        try {
            $strErrorMessage = '';
            $arrResponse = $ObjLinkedin->postToCompany($intCompanyPageId, $strStatusMessage);
            // not post given error
            if ($arrResponse['updateKey'] == "") {
                $strErrorMessage = "SET ERROR MESSAGE";
            }

        } catch (Exception $e) {

        }
        return $strErrorMessage;
    }
}



?>
