<?php
require_once("dbcontroller.php");
/* 
Property details
*/
Class Properties {
	const STATUS_OK = 'OK';
    const STATUS_ERR = 'ERROR';
    const IMG_URL = SITE_URL.'/property_images/';

	//private $property = array();
	public function getAllProperty(){
		$dbcontroller = new DBController();
		//echo "retertertret";
		
		if(isset($_REQUEST['APIKey'])){
			$APIKey = $_REQUEST['APIKey'];
			$property_datas = array();
			$sitequery = "SELECT * FROM `sites` where APIKey='".$APIKey."'";
			$SiteDetails = $dbcontroller->executeSelectQuerySingleRow($sitequery);
			if(!empty($SiteDetails))
			{
				//echo "<pre>";print_r($SiteDetails);echo "</pre>";
				$siteID = $SiteDetails['ID'];
				$property_title = $SiteDetails['Title'];
				$Properyquery = "SELECT site_id,GROUP_CONCAT(property_id) as propertiIDS FROM `sites_porperty` where site_id='".$siteID."' group by site_id";
				$PropertyDetails = $dbcontroller->executeSelectQuerySingleRow($Properyquery);
				if($PropertyDetails)
				{
					$updatequery = "UPDATE sites SET view_hint = view_hint + 1 where ID = '".$siteID."' ";
					$updatesite = $dbcontroller->executeQuery($updatequery);
					$propertiIDS = $PropertyDetails['propertiIDS'];
					$query = 'SELECT * FROM properties where ID in ('.$propertiIDS.')';
					//echo "<pre>";print_r($dbcontroller->executeSelectQuery($query));echo "</pre>";
					$Properties = $dbcontroller->executeSelectQuery($query);
					$img_url = self::IMG_URL;
					if($Properties)
					{
						$kk=0;
						foreach($Properties as $property)
						{
							$property_datas[$kk] = $property; 
							$property_id = $property['ID'];
							$property_imagesq = "SELECT CONCAT('".$img_url."', image_name) AS image_url,image_name FROM property_images where property_id = $property_id";
							//echo "<pre>";print_r($dbcontroller->executeSelectQuery($query));echo "</pre>";
							$property_images = $dbcontroller->executeSelectQuery($property_imagesq);
							$property_datas[$kk]['images'] = $property_images;
							$property_featureq = "SELECT feature_name,feature_value FROM property_feature where property_id = $property_id";
							//echo "<pre>";print_r($dbcontroller->executeSelectQuery($query));echo "</pre>";
							$property_feature = $dbcontroller->executeSelectQuery($property_featureq);
							$property_datas[$kk]['features'] = $property_feature;
							$kk++;
						}
						
					}
					$prop_count = sizeof($property_datas);
					return json_encode(array('status' => self::STATUS_OK, 'statusCode' => 200, 'response' => array('property_list' => $property_datas,'property_count' => $prop_count ,'limit' => 2 ,'property_title' =>$property_title)));

				}
				else
				{
					return json_encode(array('status' => self::STATUS_ERR, 'statusCode' => 200,'response' => array('status_message' => 'Property not added this APIKey')));
				}
				
			}
			else
			{
				return json_encode(array('status' => self::STATUS_ERR, 'statusCode' => 401,'response' => array('status_message' => 'Unauthorized APIKey.Please enter a valid APIKey.')));
			}
			
		} else {
			return json_encode(array('status' => self::STATUS_ERR, 'statusCode' => 400,'response' => array('status_message' => 'APIKey not found')));
		}
	}



	public function searchProperties(){
		$dbcontroller = new DBController();
		//echo "retertertret";
		
		if(isset($_REQUEST['APIKey'])){
			$APIKey = $_REQUEST['APIKey'];

			$limit =4;

			$get_pagination = trim ($_POST['page']);

if($_POST['page']=='' ) { $get_pagination =1;}

$page_counts = ($get_pagination *$limit) - $limit;

/**** property values ******/

$country =$_POST['country'];
$region=$_POST['region'];
   $Title =$_POST['Title'];
  $Description =$_POST['Description'];
  $Featured =$_POST['Featured'];
  $RefNo =$_POST['RefNo']; 
  $Seller =$_POST['Seller'];
   $Source =$_POST['Source'];
   $Lat =$_POST['Lat'];
   $Tenure =$_POST['Tenure'];
   $Qualifier =$_POST['Qualifier'];
    $Price =$_POST['Price'];
   $priceperm2 =$_POST['priceperm2'];
   $Bedrooms =$_POST['Bedrooms'];

$bathrooms =$_POST['bathrooms'];
$floorSize =$_POST['floorSize'];
$furnished =$_POST['furnished'];
$parking =$_POST['parking'];
$garden =$_POST['garden'];
$DistanceSea =$_POST['DistanceSea'];
$DistanceAirport =$_POST['DistanceAirport'];
$NearestAirport =$_POST['NearestAirport'];
$sold =$_POST['sold'];
$live =$_POST['live'];
$underOffer =$_POST['underOffer'];
$PropertyType =$_POST['PropertyType'];
$SaleType =$_POST['SaleType'];
$PropertyType =$_POST['PropertyType'];
$full_address =$_POST['full_address'];
$Town =$_POST['Town'];
$zip_code =$_POST['zip_code'];
$HideInFeeds =$_POST['HideInFeeds'];

$where_dataP  ='';

if($country !='')
{
	$where_dataP .= " and country='$country'";
}

if($region !='')
{
	$where_dataP .= " and region='$region'";
}
if($Title !='')
{
	$where_dataP .= " and Title='$Title'";
}
if($Description !='')
{
	$where_dataP .= " and Description='$Description'";
}
if($Featured !='')
{
	$where_dataP .= " and Featured='$Featured'";
}
if($RefNo !='')
{
	$where_dataP .= " and RefNo='$RefNo'";
}

if($Seller !='')
{
	$where_dataP .= " and Seller='$Seller'";
}
if($Source !='')
{
	$where_dataP .= " and Source='$Source'";
}
if($Lat !='')
{
	$where_dataP .= " and Lat='$Lat'";
}
if($lng !='')
{
	$where_dataP .= " and lng='$lng'";
}
if($Tenure !='')
{
	$where_dataP .= " and Tenure='$Tenure'";
}
if($Price !='')
{
	$where_dataP .= " and Price='$Price'";
}
if($priceperm2 !='')
{
	$where_dataP .= " and priceperm2='$priceperm2'";
}
if($Bedrooms !='')
{
	$where_dataP .= " and Bedrooms='$Bedrooms'";
}
if($floorSize !='')
{
	$where_dataP .= " and floorSize='$floorSize'";
}
if($furnished !='')
{
	$where_dataP .= " and furnished='$furnished'";
}
if($parking !='')
{
	$where_dataP .= " and parking='$parking'";
}
if($garden !='')
{
	$where_dataP .= " and garden='$garden'";
}
if($DistanceSea !='')
{
	$where_dataP .= " and DistanceSea='$DistanceSea'";
}
if($DistanceAirport !='')
{
	$where_dataP .= " and DistanceAirport='$DistanceAirport'";
}
if($NearestAirport !='')
{
	$where_dataP .= " and NearestAirport='$NearestAirport'";
}
if($sold !='')
{
	$where_dataP .= " and sold='$sold'";
}
if($live !='')
{
	$where_dataP .= " and live='$live'";
}
if($underOffer !='')
{
	$where_dataP .= " and underOffer='$underOffer'";
}
if($PropertyType !='')
{
	$where_dataP .= " and PropertyType='$PropertyType'";
}
if($SaleType !='')
{
	$where_dataP .= " and SaleType='$SaleType'";
}
if($full_address !='')
{
	$where_dataP .= " and full_address='$full_address'";
}
if($Town !='')
{
	$where_dataP .= " and Town='$Town'";
}
if($zip_code !='')
{
	$where_dataP .= " and zip_code='$zip_code'";
}
if($HideInFeeds !='')
{
	$where_dataP .= " and HideInFeeds='$HideInFeeds'";
}

/**** property values end  ******/
 


			
			$property_datas = array();
			$sitequery = "SELECT * FROM `sites` where APIKey='".$APIKey."'";
			$SiteDetails = $dbcontroller->executeSelectQuerySingleRow($sitequery);

			if(!empty($SiteDetails))
			{
				//echo "<pre>";print_r($SiteDetails);echo "</pre>";
				$siteID = $SiteDetails['ID'];
				$property_title = $SiteDetails['Title'];
				//$total_results = " SELECT count(*) as total_propcount FROM sites_porperty WHERE site_id = $siteID ";

				$total_results ="SELECT  count(*) as total  FROM ( SELECT sitprop.site_id,sitprop.property_id as propertiIDS FROM sites_porperty as sitprop,properties as prop where sitprop.site_id='".$siteID."' and sitprop.property_id=prop.ID  $where_dataP order by prop.Price ) AS propertiIDS";


					$details = $dbcontroller->executeSelectQuerySingleRow($total_results);	

					if(!empty($details))

					{		$total_count_stl = $details['total'];
					}
						

						$Properyquery = " SELECT site_id,GROUP_CONCAT(propertiIDS) FROM ( SELECT site_id,property_id as propertiIDS FROM sites_porperty where site_id='".$siteID."' LIMIT $page_counts,$limit ) AS propertiIDS";

					$property_order = (isset($_REQUEST['property_order']))?$_REQUEST['property_order']:'price-asc';

					if($property_order  == 'price-asc')
					{
						//$order_by_qry = 'Price asc';
						  $Properyquery  = "SELECT site_id,GROUP_CONCAT(propertiIDS) FROM ( SELECT sitprop.site_id,sitprop.property_id as propertiIDS FROM sites_porperty as sitprop,properties as prop where sitprop.site_id='".$siteID."' and sitprop.property_id=prop.ID  $where_dataP order by prop.Price   asc LIMIT $page_counts,$limit ) AS propertiIDS";
						
					}
					else if($property_order  == 'price-desc')
					{
						//$order_by_qry = 'Price desc';
						$Properyquery  = "SELECT site_id,GROUP_CONCAT(propertiIDS) FROM ( SELECT sitprop.site_id,sitprop.property_id as propertiIDS FROM sites_porperty as sitprop,properties as prop where sitprop.site_id='".$siteID."' and sitprop.property_id=prop.ID  $where_dataP order by prop.Price desc LIMIT $page_counts,$limit ) AS propertiIDS";
					}
					else if($property_order  == 'popular')
					{
						//$order_by_qry = 'popular';
						//$Properyquery = " SELECT site_id,GROUP_CONCAT(propertiIDS) FROM ( SELECT site_id,property_id as propertiIDS FROM sites_porperty where site_id='".$siteID."' LIMIT $page_counts,$limit ) AS propertiIDS";
					$Properyquery  = "SELECT site_id,GROUP_CONCAT(propertiIDS) FROM ( SELECT sitprop.site_id,sitprop.property_id as propertiIDS FROM sites_porperty as sitprop,properties as prop where sitprop.site_id='".$siteID."' and sitprop.property_id=prop.ID  $where_dataP  LIMIT $page_counts,$limit ) AS propertiIDS";
					}
					else
					{
						//$order_by_qry = 'Price asc';
						$Properyquery="SELECT site_id,GROUP_CONCAT(propertiIDS) FROM ( SELECT sitprop.site_id,sitprop.property_id as propertiIDS FROM sites_porperty as sitprop,properties as prop where sitprop.site_id='".$siteID."' and sitprop.property_id=prop.ID  $where_dataP order by prop.ID desc LIMIT $page_counts,$limit ) AS propertiIDS";
					}
		

				$PropertyDetails = $dbcontroller->executeSelectQuerySingleRow($Properyquery);

					// echo '<pre>'; print_r($PropertyDetails );echo '</pre>';
				if($PropertyDetails)
				{

					

					//$updatequery = "UPDATE sites SET search_hint = search_hint + 1 where ID = '".$siteID."' ";
					//$updatesite = $dbcontroller->executeQuery($updatequery);

					$propertiIDS = $PropertyDetails['GROUP_CONCAT(propertiIDS)'];

					$where_data = '';
					$term = (isset($_REQUEST['term']))?$_REQUEST['term']:'';
					$minprice = (isset($_REQUEST['minprice']))?$_REQUEST['minprice']:'';
					$maxprice = (isset($_REQUEST['maxprice']))?$_REQUEST['maxprice']:'';
					$minpricem2 = (isset($_REQUEST['minpricem2']))?$_REQUEST['minpricem2']:'';
					$maxpricem2 = (isset($_REQUEST['maxpricem2']))?$_REQUEST['maxpricem2']:'';
					$bedrooms = (isset($_REQUEST['bedrooms']))?$_REQUEST['bedrooms']:'';
					$bathrooms = (isset($_REQUEST['bathrooms']))?$_REQUEST['bathrooms']:'';
					$floorSize = (isset($_REQUEST['floorSize']))?$_REQUEST['floorSize']:'';
					$featured = (isset($_REQUEST['featured']))?$_REQUEST['featured']:'';
					$furnished = (isset($_REQUEST['furnished']))?$_REQUEST['furnished']:'';
					$sold = (isset($_REQUEST['sold']))?$_REQUEST['sold']:'';
					$live = (isset($_REQUEST['live']))?$_REQUEST['live']:'';
					$under_offer = (isset($_REQUEST['under_offer']))?$_REQUEST['under_offer']:'';
					$hideinfeeds = (isset($_REQUEST['hideinfeeds']))?$_REQUEST['hideinfeeds']:'';
					$garden = (isset($_REQUEST['garden']))?$_REQUEST['garden']:'';
					$parking = (isset($_REQUEST['parking']))?$_REQUEST['parking']:'';

					if($term !='')
					{
						//$where_data .= " and CONCAT(Title,Description,RefNo,Seller,Source,Lat,Lng,Tenure,Qualifier,DistanceSea,DistanceAirport,NearestAirport,sold,live,underOffer,PropertyType,SaleType,Country,Town,Region) like '%".$term."%'";
						$where_data .= " and (Title like '%".$term."%' or Description like '%".$term."%' or RefNo like '%".$term."%' or Seller like '%".$term."%' or Source like '%".$term."%' or Lat like '%".$term."%' or Lng like '%".$term."%' or Tenure like '%".$term."%' or Qualifier like '%".$term."%' or DistanceSea like '%".$term."%' or DistanceAirport like '%".$term."%' or NearestAirport like '%".$term."%' or underOffer like '%".$term."%' or PropertyType like '%".$term."%' or SaleType like '%".$term."%' or Country like '%".$term."%' or Town like '%".$term."%' or Region like '%".$term."%')";
					}
					if($minprice !='' )
					{
						$where_data .= " and Price>='".$minprice."'";
					}
					if($maxprice !='')
					{
						$where_data .= " and Price<='".$maxprice."'";
					}
					if($minpricem2 !='' )
					{
						$where_data .= " and priceperm2>='".$minpricem2."'";
					}
					if($maxpricem2 !='')
					{
						$where_data .= " and priceperm2<='".$maxpricem2."'";
					}
					if($bedrooms !='')
					{
						$where_data .= " and Bedrooms='".$bedrooms."'";
					}
					if($bathrooms !='')
					{
						$where_data .= " and bathrooms='".$bathrooms."'";
					}
					if($floorSize !='')
					{
						$where_data .= " and floorSize='".$floorSize."'";
					}
					if($featured !='')
					{
						$where_data .= " and Featured='".$featured."'";
					}
					if($furnished !='')
					{
						$where_data .= " and furnished='".$furnished."'";
					}
					if($sold !='')
					{
						$where_data .= " and sold='".$sold."'";
					}
					if($live !='')
					{
						$where_data .= " and live='".$live."'";
					}
					if($under_offer !='')
					{
						$where_data .= " and underOffer='".$under_offer."'";
					}
					if($hideinfeeds !='')
					{
						$where_data .= " and HideInFeeds='".$hideinfeeds."'";
					}
					if($garden !='')
					{
						$where_data .= " and garden='".$garden."'";
					}
					if($parking !='')
					{
						$where_data .= " and parking='".$parking."'";
					}

					// if($_POST['order_by'] !='')
					// {
					// 	$where_data .= "ORDER BY Price ASC";
					// }


					//if($term !='')
					//{
						//$query = "SELECT * FROM `properties` where ID in (".$propertiIDS.") and CONCAT(Title,Description,Featured,RefNo,Seller,Source,Lat,Lng,Tenure,Qualifier,Price,Bedrooms,bathrooms,floorSize,furnished,parking,garden,DistanceSea,DistanceAirport,NearestAirport,sold,live,underOffer,PropertyType,SaleType,Country,Town,Region,HideInFeeds) like '%".$term."%' ";

					 $query = "SELECT * FROM `properties` where ID in (".$propertiIDS.") ".$where_data. "ORDER BY FIND_IN_SET(`ID`,'".$propertiIDS."')";

					 // echo "SELECT * FROM `properties` where ID in (".$propertiIDS.") ".$where_data;



					//}
					// else
					// {
					// 	$query = 'SELECT * FROM properties where ID in ('.$propertiIDS.')';
					// }
					
					//echo "<pre>";print_r($dbcontroller->executeSelectQuery($query));echo "</pre>";
					$Properties = $dbcontroller->executeSelectQuery($query);
					$img_url = self::IMG_URL;
					if($Properties)
					{
						$kk=0;
						foreach($Properties as $property)
						{

							$property_datas[$kk] = $property; 
							$property_id = $property['ID'];
							$current_time = date('Y-m-d H:i:s');
							$insertquery = "Insert into Statistics (SiteID,PropertyID,Date,hitType) value ('".$siteID."','".$property_id."','".$current_time."','property_search') ";
							$updatesite = $dbcontroller->executeQuery($insertquery);


							$property_imagesq = "SELECT CONCAT('".$img_url."', image_name) AS image_url,image_name FROM property_images where property_id = $property_id";
							//echo "<pre>";print_r($dbcontroller->executeSelectQuery($query));echo "</pre>";
							$property_images = $dbcontroller->executeSelectQuery($property_imagesq);
							$property_datas[$kk]['images'] = $property_images;
							$property_featureq = "SELECT feature_name,feature_value FROM property_feature where property_id = $property_id";
							//echo "<pre>";print_r($dbcontroller->executeSelectQuery($query));echo "</pre>";
							$property_feature = $dbcontroller->executeSelectQuery($property_featureq);
							$property_datas[$kk]['features'] = $property_feature;
							$kk++;
						}
						
					}
					$prop_count = sizeof($property_datas);
				
					$pagination_counts = ceil($total_count_stl / $limit );

						$page_counts =$page_counts + 1;
					
				
						return json_encode(array('status' => self::STATUS_OK, 'statusCode' => 200, 'response' => array('property_list' => $property_datas,'property_count' => $prop_count, 'pagination_counts' =>$pagination_counts ,'limit' => $limit ,'property_title' =>$property_title,'total_properties' =>$total_count_stl ,'start_limt' =>$page_counts)));
						
					
					
				}
				else
				{
					return json_encode(array('status' => self::STATUS_ERR, 'statusCode' => 200,'response' => array('status_message' => 'Property not added this APIKey')));
				}
				
			}
			else
			{
				return json_encode(array('status' => self::STATUS_ERR, 'statusCode' => 401,'response' => array('status_message' => 'Unauthorized APIKey.Please enter a valid APIKey.')));
			}
			
		} else {
			return json_encode(array('status' => self::STATUS_ERR, 'statusCode' => 400,'response' => array('status_message' => 'APIKey not found')));
		}
	}

	public function propertiesDetails(){
		$dbcontroller = new DBController();
		//echo "retertertret";
		
		if(isset($_REQUEST['APIKey'])){
			$APIKey = $_REQUEST['APIKey'];
			$PropertyTerm = $_REQUEST['PropertyTerm'];

			$property_datas = array();
			$sitequery = "SELECT * FROM `sites` where APIKey='".$APIKey."'";
			$SiteDetails = $dbcontroller->executeSelectQuerySingleRow($sitequery);
			if(!empty($SiteDetails))
			{
				//echo "<pre>";print_r($SiteDetails);echo "</pre>";
				$siteID = $SiteDetails['ID'];
				// $Properyquery = "SELECT sp_id,site_id,property_id FROM `sites_porperty` where site_id='".$siteID."' and (property_id='".$PropertyTerm."' or RefNo='".$PropertyTerm."') ";
				$Properyquery = "SELECT sp_id,site_id,property_id FROM `sites_porperty` where site_id='".$siteID."' and (property_id='".$PropertyTerm."' or property_id in (select ID from properties where RefNo='".$PropertyTerm."'))";
				$PropertyDetails = $dbcontroller->executeSelectQuerySingleRow($Properyquery);
				//echo "<pre>";print_r($PropertyDetails);echo "</pre>";
				if($PropertyDetails)
				{
					$sp_id = $PropertyDetails['sp_id'];
					$PropertyID = $PropertyDetails['property_id'];
					
					
					$query = "SELECT * FROM properties where ID ='".$PropertyID."'";
					$img_url = self::IMG_URL;
					
					$Properties = $dbcontroller->executeSelectQuerySingleRow($query);
					if($Properties)
					{
						$kk=0;
						//foreach($Properties as $property)
						//{
							$property_datas = $Properties; 
							$property_id = $Properties['ID'];

							$current_time = date('Y-m-d H:i:s');
					 		$insertquery = "Insert into Statistics (SiteID,PropertyID,Date,hitType) value ('".$siteID."','".$property_id."','".$current_time."','property_details')";
							$updatesite = $dbcontroller->executeQuery($insertquery);

							$property_imagesq = "SELECT CONCAT('".$img_url."', image_name) AS image_url, image_name FROM property_images where property_id = $property_id";
							//echo "<pre>";print_r($dbcontroller->executeSelectQuery($query));echo "</pre>";
							$property_images = $dbcontroller->executeSelectQuery($property_imagesq);
							$property_datas['images'] = $property_images;
							$property_featureq = "SELECT feature_name,feature_value FROM property_feature where property_id = $property_id";
							//echo "<pre>";print_r($dbcontroller->executeSelectQuery($query));echo "</pre>";
							$property_feature = $dbcontroller->executeSelectQuery($property_featureq);
							$property_datas['features'] = $property_feature;
							//$kk++;
						//}
						
					}
					$prop_count = sizeof($property_datas);
				
						return json_encode(array('status' => self::STATUS_OK, 'statusCode' => 200, 'response' => array('property_details' => $property_datas)));
					
					
				}
				else
				{
					return json_encode(array('status' => self::STATUS_ERR, 'statusCode' => 200,'response' => array('status_message' => 'Property not added this APIKey')));
				}
				
			}
			else
			{
				return json_encode(array('status' => self::STATUS_ERR, 'statusCode' => 401,'response' => array('status_message' => 'Unauthorized APIKey.Please enter a valid APIKey.')));
			}
			
		} else {
			return json_encode(array('status' => self::STATUS_ERR, 'statusCode' => 400,'response' => array('status_message' => 'APIKey not found')));
		}
	}
	
	


	/*public function addMobile(){

		echo "<pre>";print_r($_REQUEST);echo "</pre>";
		//echo 'ttttttttt';exit;
		
		if(isset($_REQUEST['name'])){
			$name = $_REQUEST['name'];
				$model = '';
				$color = '';
			if(isset($_REQUEST['model'])){
				$model = $_REQUEST['model'];
			}
			if(isset($_REQUEST['color'])){
				$color = $_REQUEST['color'];
			}	
			//echo 'fffffffff';exit;
			$query = "insert into tbl_mobile (name,model,color) values ('" . $name ."','". $model ."','" . $color ."')";
			$dbcontroller = new DBController();
			$result = $dbcontroller->executeQuery($query);
			if($result != 0){
				$result = array('success'=>1);
				return $result;
			}
		}
	}
	
	public function deleteMobile(){
		if(isset($_GET['id'])){
			$id = $_GET['id'];
			$query = 'DELETE FROM tbl_mobile WHERE id = '.$id;
			$dbcontroller = new DBController();
			$result = $dbcontroller->executeQuery($query);
			if($result != 0){
				$result = array('success'=>1);
				return $result;
			}
		}
	}
	
	public function editMobile(){
		if(isset($_POST['name']) && isset($_GET['id'])){
			$name = $_POST['name'];
			$model = $_POST['model'];
			$color = $_POST['color'];
			$query = "UPDATE tbl_mobile SET name = '".$name."',model ='". $model ."',color = '". $color ."' WHERE id = ".$_GET['id'];
		}
		$dbcontroller = new DBController();
		$result= $dbcontroller->executeQuery($query);
		if($result != 0){
			$result = array('success'=>1);
			return $result;
		}
	}*/
	
}
?>
