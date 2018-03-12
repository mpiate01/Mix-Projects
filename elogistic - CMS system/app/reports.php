<?php
require_once 'core/init.php';
require_once 'includes/templates/header.php';
$display = false;

$user = new User();

if ($user->getIsLoggedIn())
{
	if($user->getHasPermission('admin'))  //e' stato usato un json value
	{
		echo '<p>You are an administrator!</p>';
	
		//Input check if $_POST or $_GET exist
		if(Input::exists())
		{			
			if (Token::checkToken(Input::get('token')))		//get('token') viene preso dalla form
			{
				$validate = new Validate();

				$validation = $validate->check($_POST, array(
					'id'			=> array(
						'name_error' 	=> 'User',	
						'exists'		=> array ('facilities' => 'id')
					)
				));
				if(Input::get('id') == 0)
				{
					$validation->setPassed(false);
				}

				//Register User
				if ($validation->getPassed())
				{	
					$display = true	;				
				}
				else
				{
					$errors = $validation->getErrors();
				}	
			}
		}
require_once 'includes/templates/header.php';
if(isset($errors))
{
	foreach($errors as $error)
	{
		echo '<p class="error">' . $error . '</p>';
	}
}

		?>



		<form action="" method="post">			
			<div class="field">
				<label for="id">Select facility *</label>
				<select id="id" name="id">
				<option value="0"></option>
				<?php				
				$users = new User();
					if ($users->setInfoAllUsers())
					{
						foreach($users->getInfoUser() as $row)
						{
							if($user->getInfoUser()->id != $row->id)
							{
								echo '<option value="' . $row->id . '">' . $row->username . '</option>';
							}
						}
					}					
				?>
				</select>
			</div>
			
			<!--TOKEN PER SECURITY CHECK -->
			<input type="hidden" name="token" value="<?php echo Token::generateToken() ?>"> 
			<!--<input type="hidden" name="reset_value" value="1"> -->
			<input type="submit" value="View orders" name="submit">
		</form>
		<p>Go back to <a href="index.php">home page</a></p>
<?php

	}
	else
	{
		echo "<p>You haven't got the right level of permission. " . 'Please go to <a href="index.php">home page</a></p>';
	}
}
else
{?>
	<p>You are not logged in at the moment. Go to <a href="login.php">log in</a></p>
	<?php
}

if($display)
{
	echo '<div class="elenco">';
	$report = new Report();
	if($report->setInfoOrdersFacility(Input::get('id')))
	{
		foreach ($report->getInfoReport() as $row) 
		{
			echo '<p><a href="reports.php?order=' . $row->id . '">Order No '  . $row->id	 .'</a></p>';
			$reports[$row->id] = '1' ;
		}
		Session::setSession('reports', $reports);
	}
	else
	{
		echo '<p><strong>No orders have been found!</strong></p>';	
	}
	echo '</div>';
}

if(Input::get('order'))
{
	if(Session::existsSession('reports'))
	{
			$check = Session::getSession('reports');
		
		if (is_array($check))
		{		
			if (array_key_exists(Input::get('order'), $check))
			{
				$report = new Report();
				if($report->setInfoOrderPackages(Input::get('order')))
				{
					echo '<div class="pack">';
					echo '<h2>Order No: ' . Input::get('order') . '</h2>';
					$i = 0;
					foreach ($report->getInfoReport() as $row) 
					{
						$package = $i+1;
	  					$weight = 	$row->weight;
	  					$height =	$row->height;
	  					$length =  	$row->length;
	  					$width  =	$row->width;
	  					$description = $row->description;


	  					$output = '';
	  					$output = '<p><u>Package n. ' . $package . '</u></p>';
	  					$output .= '<p><strong>Weight</strong>: ' .  $weight;
	  					$output .= ', <strong>Height</strong>: ' . $height;
	  					$output .= ', <strong>Length</strong>: ' .  $length;
	  					$output .= ', <strong>Width</strong>: ' . $width;
	  					$output .= ', <strong>Description</strong>: ' . $description . '</p>';
	  					echo $output;
		  				$i++;
					}
					Session::deleteSession('reports');
					unset($_GET);
					echo '</div>';
				}		
			}
		}	
	}
}
require_once 'includes/templates/footer.php';