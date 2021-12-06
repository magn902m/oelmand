<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

<?php if ( astra_page_layout() == 'left-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

	<div id="primary" <?php astra_primary_class(); ?>>

		<?php astra_primary_content_top(); ?>

		

		<?php astra_primary_content_bottom(); ?>

		<main id="single-produkt-main">
					<section id="produkt-oversigt">
						<button id="back_knap">Tilbage til katalog</button>
						<article id="hoved_info">
							<div class="left_col">
								<img class="billede" src="" alt="">
							</div>
							<div class="right_col">
								<h2 class="titel"></h2>
								<p><b>Nationalitet</b><span class="nationalitet"></span></p>
								<p class="oeltype"></p>
								<p class="bryggeri"></p>
								<p class="smag"></p>
								<p class="pris"></p>
								<p class="info"></p>
								<p class="procent"></p>
							</div>
						</article>
					</section>
				</main><!-- #main -->

				<script>
					// const urlParams = new URLSearchParams(window.location.search);
					// const id = urlParams.get("id");
					
					let produkt;

					const url = "https://designbymagnus.dk/kea/2_semester/tema10/oelmand/wp-json/wp/v2/produkt/" + <?php echo get_the_ID() ?>;

					async function getJSON() {
						const response = await fetch(url);
						produkt = await response.json();

						console.log(produkt);

						visProdukt();
					}

					function visProdukt() {
						document.querySelector(".billede").src = produkt.billede.guid;
						document.querySelector(".titel").textContent = produkt.navn;
						document.querySelector(".pris").textContent = "Pris: " + produkt.pris + " kr.-";
						document.querySelector(".oeltype").textContent = "Øltype: " + produkt.vaelg_oeltype;
						document.querySelector(".nationalitet").textContent = "Nationalitet: " + produkt.vaelg_nationalitet;
						document.querySelector(".smag").textContent = "Smag: " + produkt.smag;
						document.querySelector(".bryggeri").textContent = "Bryggeri: " + produkt.vaelg_byggeri;
						document.querySelector(".info").textContent = "Produktbeskrivelse: " + produkt.info;
						document.querySelector(".procent").textContent = "Alkohol procent: " + produkt.procent;
					}
					

					document.querySelector("#back_knap").addEventListener("click", () => {
					window.history.back();
					});

					getJSON();
				</script>

	</div><!-- #primary -->

<?php if ( astra_page_layout() == 'right-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

<?php get_footer(); ?>
