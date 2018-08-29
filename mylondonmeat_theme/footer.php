<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ceight
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer<?php if ( get_background_image() ){ echo ' back-white';} ?>" role="contentinfo">
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'ceight' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'ceight' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'ceight' ), 'ceight', '<a href="http://c-eight.com" rel="designer">c-eight.com</a>' ); ?>
			<div id ="paymentsGate">
				<!-- Individual logos-->		
				<img src=http://www.worldpay.com/images/cardlogos/VISA.gif border=0 alt="Visa Credit and Debit payments supported by Worldpay">
				<img src=http://www.worldpay.com/images/cardlogos/visa_electron.gif border=0 alt="Visa Electron payments supported by Worldpay">
				<img src=http://www.worldpay.com/images/cardlogos/mastercard.gif border=0 alt="Mastercard payments supported by Worldpay">
				<img src=http://www.worldpay.com/images/cardlogos/maestro.gif border=0 alt="Maestro payments supported by Worldpay">
				<img src=http://www.worldpay.com/images/cardlogos/amex-logo2.gif border=0 alt="American Express payments supported by Worldpay">
				<!-- Powered by Worldpay logo-->
				<a href="http://www.worldpay.com/" target="_blank" title="Payment Processing - Worldpay - Opens in new browser window"><img src="http://www.worldpay.com/images/poweredByWorldPay.gif" border="0" alt="Worldpay Payments Processing"></a>
			</div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
