<?php
require_once 'core/init.php';

$user = new User();


if(!$user->getIsLoggedIn())
{
	Redirect::to('index.php');
}
else
{

	//Input check if $_POST or $_GET exist
		if(Input::exists())
		{
			if (Token::checkToken(Input::get('token')))		//get('token') viene preso dalla form
			{
				//the whole date is saved in $_POST['year'] to be validated
				if (Input::get('year'))
				{
				 	$_POST['year'] = array(
				 		'year' 	=> Input::get('year'), 
				 		'month' => Input::get('month'), 
				 		'day'	=> Input::get('day')
				 	);
				 	Input::unset('post','month');
				 	Input::Unset('post','day');
				}
				//the whole time and date is saved in $_POST['time'] to be validated
				if(Input::get('hour'))
				{
					//CHECK FROM INJECTED CODE CAN BE IMPROVED IN TIME
					$time = mktime(intval($_POST['hour']), intval($_POST['minute']), '00', $_POST['year']['month'], $_POST['year']['day'], $_POST['year']['year']   );
					
					$_POST['hour'] = date("Y-m-d h:i:s", $time);
					Input::unset('post','minute');;
				}

				//If a new address has been enterd, Saved Location wont be used
				if (!empty(Input::get('p_address')))
				{
					Input::unset('post', 'address_id');
				}
				else
				{
					Input::unset('post', 'p_address');
					Input::unset('post', 'p_postcode');
					Input::unset('post', 'p_city');
					Input::unset('post', 'p_country');
				}

				if (!empty($_POST['d_address_id']))
				{
					Input::unset('post', 'd_address_id');
				}
				else
				{
					Input::unset('post', 'd_address');
					Input::unset('post', 'd_postcode');
					Input::unset('post', 'd_city');
					Input::unset('post', 'd_country');
				}
				$validate = new Validate();
				$validation = $validate->check($_POST, array(
					'shipment'				=> array(
						'name_error'	=> 'Shipment type',
						'exists'		=> array ('shipments' => 'shipment') 	// table_name => $POST[name]
					),
					'address_id'				=> array(
						'name_error'	=> 'Favourite Pickup location',
						'required' 	=> true,
						'existing_favourite_location'	=> array ('favourite_locations' => 'address_id') 	
					),
					'd_address_id'				=> array(
						'name_error'	=> 'Favourite Delivery location',
						'required' 	=> true,
						'existing_favourite_location'	=> array ('favourite_locations' => 'd_address_id') 	
					),
					'p_address'				=> array(
						'name_error'	=> 'Address',
						'required' 	=> true,
						'min'      	=> 5,
						'max'      	=> 30   	
					),
					'p_postcode'				=> array(
						'name_error'	=> 'Postcode',
						'required' 	=> true,
						'min'      	=> 2,
						'max'      	=> 12   	
					),
					'p_city'				=> array(
						'name_error'	=> 'City',
						'required' 	=> true,
						'min'      	=> 4,
						'max'      	=> 20   	
					),
					'p_country'			=> array(
						'name_error'	=> 'Country',
						'required' 	=> true,
						'min'      	=> 4,
						'max'      	=> 20   	
					),
					'd_address'				=> array(
						'name_error'	=> 'Address',
						'required' 	=> true,
						'min'      	=> 5,
						'max'      	=> 30   	
					),
					'd_postcode'				=> array(
						'name_error'	=> 'Postcode',
						'required' 	=> true,
						'min'      	=> 2,
						'max'      	=> 12   	
					),
					'd_city'				=> array(
						'name_error'	=> 'City',
						'required' 	=> true,
						'min'      	=> 4,
						'max'      	=> 20   	
					),
					'd_country'			=> array(
						'name_error'	=> 'Country',
						'required' 	=> true,
						'min'      	=> 4,
						'max'      	=> 20   	
					),
					'year'			=> array(
						'name_error'	=> 'Date pickup',
						'required'		=> true, 
						'valid_date'	=> $_POST['year']
					),
					'hour'			=> array(
						'name_error'	=> 'Time pickup',
						'required'		=> true, 
						'time_pickup_correct'	=> $_POST['hour']
					),
					'extra_info'	=> array(
						'name_error'	=> 'Extra info',
						'min'      	=> 4,
						'max'      	=> 40   	
					)
				));
				if ($validation->getPassed())
				{
									
					if(!Input::get('address_id'))
					{
						$from_addr = array(
							'address' 	=> trim(Input::get('p_address')),
							'post_code'	=> trim(Input::get('p_postcode')),
							'city'		=> trim(Input::get('p_city')),
							'country'	=> trim(Input::get('p_country'))
						);
					}
					else
					{
						$from_addr = Input::get('address_id');
					}

					if(!Input::get('d_address_id'))
					{
						$to_addr = array(
						'address' 	=> trim(Input::get('d_address')),
						'post_code'	=> trim(Input::get('d_postcode')),
						'city'		=> trim(Input::get('d_city')),
						'country'	=> trim(Input::get('d_country')),
						'to_addr'	=> 1
					);
					}
					else
					{
						$to_addr = Input::get('d_address_id');
					}
										
					$from_date = Input::get('hour');
					//Order saved in a SESSION, the order will be stored in the database only after all packages details are entered 
					$_SESSION['order'] = array(
						'facility_id'	=> $user->getInfoUser()->id,	
						'date'			=> date("Y-m-d h:i:s"),
						'shipment_id'	=> Input::get('shipment'),
						'from_addr'	=> $from_addr,
						'to_addr'	=> $to_addr,
						'from_date'	=> $from_date,
						'extra_info'=> Input::get('extra_info')
					);
					Session::flashMessage('continue_order', 'Please continue your order by adding 1 or more packages.');
					//Redirect::to('addpackage.php');
					$_SESSION['order'] = '';
				}
				else
				{
					foreach($validation->getErrors() as $error)
					{
						echo $error, '<br>';
					}
				}	
			}
		}	

if(isset($_SESSION['order'])){
		print_r($_SESSION['order']);}
?>

		<form action="" method="post">
			<div class="field">
				<label for="shipment">Type of shipment *</label>
				<select id="shipment" name="shipment">
				<?php
					$shipment_list = new Shipment();
					if ($shipment_list->setInfoShipment())
					{
						foreach($shipment_list->getInfoShipment() as $row)
						{
							$selected = (($shipment_list->id == Input::get('shipment')) || ($shipment_list->id == $_SESSION['order']['shipment_id'])) ? 'selected' : '';
							
							echo '<option value="' . $row->id . '">' . $row->name . '</option>';
						}
					}					
				?>
				</select>
			</div>
			<!--///////////////////////////////////////////////////////
			///////////////////////////////////////////////////////
			////////	pick up and delivery address
			////////
			////////	pick up da prendere da facility
			////////	delivery, nuovo  o old -> da database
			///////////////////////////////////////////////////////
			///////////////////////////////////////////////////////-->
			<div class="field">
				<label for="address_id">Saved Location *</label>
				<select id="address_id" name="address_id">
				<option value="0"></option>
				<?php
					$favourite_location = new FLocation();
					if ($favourite_location->setInfoFLocation($user->getInfoUser()->id))
					{	
						foreach($favourite_location->getInfoFLocation() as $row)
						{
							$address = new Address();
							if($address->setInfoAddrId($row->address_id))
							{
								$addInfo = $address->getInfoAddr();
							}
							$selected = (($addInfo->id == Input::get('address_id')) || ($addInfo->id == $_SESSION['order']['from_addr'])) ? ' selected ' :'';
							echo '<option value="' . $addInfo->id . '"' . $selected . '>' . $addInfo->address . ', ' . $addInfo->post_code . ', ' . $addInfo->city . ', ' . $addInfo->country . '</option>';
						}
					}					
				?>
				</select>
			</div>
			<div class="field">
				<label>New Pickup Location: </label>
				<label for="p_address">Address *</label>
				<input type="text" name="p_address" id="p_address" placeholder="Please enter Address" value="<?php echo escape(Input::get('p_address')); ?>">
				<label for="p_postcode">Postcode *</label>
				<input type="text" name="p_postcode" id="p_postcode" placeholder="Please enter Postcode" value="<?php echo escape(Input::get('p_postcode')); ?>">
				<label for="p_city">City*</label>
				<input type="text" name="p_city" id="p_city" placeholder="Please enter City" value="<?php echo escape(Input::get('p_city')); ?>">
				<label for="p_country">Country *</label>
				<input type="text" name="p_country" id="p_country" placeholder="Please enter Country" value="<?php echo escape(Input::get('p_country')); ?>">
			</div>
			<div class="field">
				<label for="d_address_id">Saved Delivery Location *</label>
				<select id="d_address_id" name="d_address_id">
				<option value="0"></option>
				<?php
					$favourite_location = new FLocation();
					if ($favourite_location->setInfoFLocation($user->getInfoUser()->id))
					{	
						foreach($favourite_location->getInfoFLocation() as $row)
						{
							$address = new Address();
							if($address->setInfoAddrId($row->address_id))
							{
								$addInfo = $address->getInfoAddr();
							}
							$selected = ($addInfo->id == Input::get('d_address_id')) ? ' selected ' :'';
							if($addInfo->to_addr != 0)
							{
								echo '<option value="' . $addInfo->id . '"' . $selected . '>' . $addInfo->address . ', ' . $addInfo->post_code . ', ' . $addInfo->city . ', ' . $addInfo->country . '</option>';
							}	
							
						}
					}					
				?>
				</select>
			</div>
			<div class="field">
				<label>Delivery Location: </label>
				<label for="d_address">Address *</label>
				<input type="text" name="d_address" id="d_address" placeholder="Please enter Address" value="<?php echo escape(Input::get('d_address')); ?>"  maxlength ="40">
				<label for="d_postcode">Postcode *</label>
				<input type="text" name="d_postcode" id="d_postcode" placeholder="Please enter Postcode" value="<?php echo escape(Input::get('d_postcode')); ?>"  maxlength ="12">
				<label for="d_city">City*</label>
				<input type="text" name="d_city" id="d_city" placeholder="Please enter City" value="<?php echo escape(Input::get('d_city')); ?>"  maxlength ="20">
				<label for="d_country">Country *</label>
				<input type="text" name="d_country" id="d_country" placeholder="Please enter Country" value="<?php echo escape(Input::get('d_country')); ?>"  maxlength ="20">
			</div>
			<div class="field">
				<label for="p_date">Pick up date *</label>
				<select id="year" name="year">
				<?php 
					for ($i= 0; $i < 4 ; $i++) 
					{ 
						echo '<option value="' . ($i + date("Y")) . '"">' . ($i + date("Y")) . '</option>';
					}
				?>	
				</select>
				<select id="month" name="month">
				<?php 
					for ($i= 1; $i < 13 ; $i++) 
					{ 
						echo '<option value="' . $i . '"">' . $i  . '</option>';
					}
				?>	
				</select>
				<select id="day" name="day">
				<?php 
					for ($i= 1; $i < 32 ; $i++) 
					{ 
						echo '<option value="' . $i . '"">' . $i  . '</option>';
					}
				?>	
				</select>
			</div>
			<div class="field">
				<label for="time">Pick up time *</label>
				<select id="hour" name="hour">
				<?php 
					for ($i= 1; $i < 25 ; $i++) 
					{ 
						echo '<option value="' . $i . '"">' . $i  . '</option>';
					}
				?>	
				</select>
				<select id="minute" name="minute">
				<?php 
					for ($i= 0; $i < 60 ; $i++) 
					{ 
						echo '<option value="' . $i . '"">' . $i  . '</option>';
					}
				?>	
				</select>				
			</div>
			<div class="field">
				<label for="extra_info">Extra information</label>
				<input type="text" name="extra_info" id="extra_info" placeholder = "Please enter here extra information" value="<?php echo escape(Input::get('extra_info')); ?>"  >
			</div>

			<!--TOKEN PER SECURITY CHECK -->
			<input type="hidden" name="token" value="<?php echo Token::generateToken() ?>"> 
			<input type="submit" value="Continue" name="submit">
		</form>
		<p>Go back to <a href="index.php">home page</a></p>
<?php





}