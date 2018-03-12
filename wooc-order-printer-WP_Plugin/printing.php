<?php
require_once woo_ord_pnt_dir . 'core/init.php';

$css = new CSS();
$css->getCSS();
$width = $css->getWidthPage();
$size = $css->getFontSizePage();


echo '<script src="https://code.jquery.com/jquery-3.2.1.min.js"   integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="  crossorigin="anonymous"></script>';
if(Session::existsSession('activation') )
{

  $orders = new Order();
 
  $orders->checkLastOrder();
  
      //SELECT ORDER WHICH IT LL PRINT FROM
    
    $vals = $orders->getListOrders();

    ?>
    <div class="wrap" >

      <div id="icon-options-general" class="icon32"> <br>
      </div>
      <h2>Order Settings</h2>' 

      <div class="metabox-holder">
        <div class="postbox">
          <h3><strong>Select the last order been printed.</strong></h3>
          <form method="post" action="" name="refreshing" id="refreshForm">
            <table class="form-table">
              <tr>
                <th scope="row">Orders List:</th>
                <td>
                  <select name="orders">
                   
                  <?php 
                  if(!is_array($vals))
                  {
                    echo '<option value="0">No new orders! Select the Save changes button</option>';
                  }
                  else
                  { 
                    echo '<option value="0">From first order</option>';

                      foreach ($vals as $key => $value) 
                      {
                        echo '<option value="' . escape($value->order_id) . '">' . escape($value->order_id) . '</option>';
                      }
                  }
                  ?>
                  </select>
                </td>
              </tr>
              <tr>
                <th scope="row">&nbsp;</th>
                <td style="padding-top:10px;  padding-bottom:10px;">
                  <input type="submit" name="order_submit" value="Save changes" class="button-primary" />
                </td>
              </tr>
            </table>
          </form>
        </div>
      </div>
    </div>
<?php    
  
  if(isset($_POST['orders']) && is_numeric($_POST['orders']))
  {
    $id = escape($_POST['orders']);
    $orders->activation_setup_plugin($id);
    Session::deleteSession('activation');
    $URL= $_SERVER['HTTP_REFERER'];
    echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
    echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
  }  

}
else
{
    if(isset($_POST['min']))
    {
      /*wphw_opt(); 
    }
    function wphw_opt()
    {*/
            
        #CAMBIARE TUTTO QUI, il resto nn penso serva
         
        #global $chk;
        #if( get_option('footer_text') != trim($hellotxt)){
        #    $chk = update_option( 'footer_text', trim($hellotxt));
        #}

        /*require_once woo_ord_pnt_dir . '/includes/page_header.php';*/

      $order_ids = new Order();
      $order_ids->checkLastOrder();
      $val = $order_ids->getInfoOrder();

      if (!is_array($val))
      {
          echo '<div id="message" class="updated below-h2"><h3>' . escape($val) . '</h3></div>';

      }
      else
      {
        for($i = 0; $i < count($val) ; $i++)
        {
            $info_customer = new Customer($val[$i]);
            $order_details = new Order_details($val[$i]);
            
              
            $header = $info_customer->getInfoCustomer();
            $output = '
            <div id="woocop_order" class="metabox-holder">
              <div class = "header">
                  <style scoped>
                    @media print{
                      div#woocop_order {
                          width :' . $width . 'cm !important;
                      }
                      #woocop_order, #woocop_order .header, #woocop_order p, #woocop_order h4{
                          font-size :' . $size . 'px !important;
                      }
                    }
                  </style> 
                <h4><strong>Order number:</strong> ' . escape($header['id_order']) . "</h4>
                <p><strong>Customer's billing details: </strong>" . escape($header['billing_details']) . "</p>
                <p><strong>Customer's delivery details: </strong>" . escape($header['shipping_details']) . '</p>
                <p><strong>Date: </strong>' . escape($header['date_order']) . '</p>
                <p><strong>Payment method: </strong>' . escape($header['payment_method']) . '</p>
                <p><strong>Shipping method: </strong>' . escape($header['shipping_method']) . '</p>
                <p><strong>Extra notes: </strong>' . escape($header['notes_order']) . '</p>
                <p><strong>Total order: </strong>' . escape($header['total_order']) . '</p> 
              </div>';
            
            $main = $order_details->getInfoOrderDetails();
            $output.= '<div class = "main">';

            $item_name = '';
            $item_qty = '';
            $item_price = '';
            $output .=  '<table>
                    <tr>
                      <th>Product name</th>
                      <th>Qty</th>
                      <th>Tot price</th>
                    </tr>';   
            for($s = 1; $s<= count($main);$s++)
            {
              if(fmod($s,2) != 0)
              {
                $item_name = $main[$s-1]->order_item_name;
                $item_qty = $main[$s-1]->meta_value;
              }
              else
              {
                $item_price = $main[$s-1]->meta_value;
                $output .= '<tr>
                      <td>' . escape($item_name) . '</td>
                      <td>' . escape($item_qty) . '</td>
                      <td>' . escape($item_price) . '</td>
                    </tr>'
                    ;
              }
            }

                    
            $output .= '</table></div></div>';

            if($i == (count($val)-1))
            {
              $output .= '<script type="text/javascript">$(document).ready(function(){
                        $("div#woocop_order").css("width","' . $width . 'cm !important");
                         $("#woocop_order, #woocop_order .header, #woocop_order p, #woocop_order h4").css("font-size","' . $size . 'px !important");
                          window.print();
                
                         });
                        </script>';
            }                   

            echo $output;
        }
      }

/*require_once woo_ord_pnt_dir . 'includes/page_footer.php';*/
      echo '<form method="post" action="" name="refreshing" id="refreshForm">
                <input type="hidden" name="min" value="' . $_POST['min'] . '">
            </form>
            <p><a class="no-print" href="' . get_admin_url() .  'admin.php?page=refreshing_setting_page">Change refreshing time</a></p>';

    }
    else
    {

?>

<div class="wrap" >

  <div id="icon-options-general" class="icon32"> <br>
  </div>
  <h2>Refreshing Settings</h2>

  <div class="metabox-holder">
    <div class="postbox">
      <h3><strong>Select the refreshing time in minutes and click on save button.</strong></h3>
      <form method="post" action="" name="refreshing" id="refreshForm">
        <table class="form-table">
          <tr>
            <th scope="row">Minutes:</th>
            <td>
              <select name="min">
                <option value="30000" 
                <?php  if(isset($_POST['wphw_submit'])){ if ($_POST['min'] == 30000){ echo 'selected="selected"';}} ?>>Half minute</option>
                <option value="60000" 
                <?php  if(isset($_POST['wphw_submit'])){ if ($_POST['min'] == 60000){ echo 'selected="selected"';}} ?>>1 minute</option>
                <option value="120000" 
                <?php  if(isset($_POST['wphw_submit'])){ if ($_POST['min'] == 120000){ echo 'selected="selected"';}} ?>>2 minutes</option>
                <option value="300000" 
                <?php  if(isset($_POST['wphw_submit'])){ if ($_POST['min'] == 300000){ echo 'selected="selected"';}} ?>>5 minutes</option>
                <option value="600000" 
                <?php  if(isset($_POST['wphw_submit'])){ if ($_POST['min'] == 600000){ echo 'selected="selected"';}} ?>>10 minutes</option>
              </select>
            </td>
          </tr>
          <tr>
            <th scope="row">&nbsp;</th>
            <td style="padding-top:10px;  padding-bottom:10px;">
<input type="submit" name="wphw_submit" value="Save changes" class="button-primary" />
</td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php
}
?>
<script type="text/javascript">
  $(document).ready(function(){
  <?php if(isset($_POST['min'])){?>
                 every_time();
  <?php }?>  
  });


  function every_time() 
  {
    setInterval(function(){ $('#refreshForm').submit(); }, <?php echo $_POST['min'];?>); 
  }
</script>
<?php
}
?>