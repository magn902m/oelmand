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

		<main id="single_produkt_main">
					<section id="produkt_oversigt">
						<button id="back_knap">Tilbage til katalog</button>
						<article id="hoved_info">
							<div class="left_col">
								<img class="billede" src="" alt="">
							</div>
							<div class="right_col">
								<h2 class="titel"></h2>
								<p><b>Nationalitet: </b><span class="nationalitet"></span></p>
								<p><b>Øltype: </b><span class="oeltype"></span></p>
								<p><b>Bryggeri: </b><span class="bryggeri"></span></p>
								<p><b>Smag: </b><span class="smag"></span></p>
								<p><b>Pris: </b><span class="pris"></span></p>
								<p><b>Produktbeskrivelse: </b><span class="info"></span></p>
								<p><b>Alkohol procent: </b><span class="procent"></span></p>
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
						document.querySelector(".pris").textContent = produkt.pris + " kr.-";
						document.querySelector(".oeltype").textContent = produkt.vaelg_oeltype;
						document.querySelector(".nationalitet").textContent = produkt.vaelg_nationalitet;
						document.querySelector(".smag").textContent = produkt.smag;
						document.querySelector(".bryggeri").textContent = produkt.vaelg_byggeri;
						document.querySelector(".info").textContent = produkt.info;
						document.querySelector(".procent").textContent = produkt.procent;
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
