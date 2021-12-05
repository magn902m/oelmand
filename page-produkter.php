<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
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

		<!-- Astra indhold starter her -->
		<?php astra_primary_content_top(); ?>

		<?php astra_content_page_loop(); ?>

		<?php astra_primary_content_bottom(); ?>
		<!-- Astra indhold slutter her -->

		<!-- REST API indhold starter her -->
		<div id="div_produkter">				
				<main id="main_produkter">
				<nav id="oeltype_filtrering">
					<button data-oeltype="alle" class="valgt">Alle</button>
				</nav>
				<nav id="nationalitet_filtrering">
					<button data-nationalitet="alle" class="valgt">Alle</button>
				</nav>
				<nav id="bryggeri_filtrering">
					<button data-bryggeri="alle" class="valgt">Alle</button>
				</nav>

				<section id="produkt_indhold">
					<article id="produkt_article"></article>
				</section>

				<template>
					<article>
						<img class="billede" src="" alt="">
						<h2 class="titel"></h2>
						<p class="pris"></p>
						<!-- <button class="videre">Læs mere</button> -->
					</article>
				</template>

		<script>
						// Indholder produkter
						let produkter = [];

						// Indholder kategorityper
						let oeltyper = [];
						let nationaliteter = [];
						let bryggerier = [];

						// filter variable
						let filterOeltype = "alle";
						let filterNationalitet = "alle";
						let filterBryggeri = "alle";

						// Url til json om produkter
						const produktUrl = "https://designbymagnus.dk/kea/2_semester/tema10/oelmand/wp-json/wp/v2/produkt?per_page=100";	
						
						// Url til json om kategorityper
						const oeltypeUrl = "https://designbymagnus.dk/kea/2_semester/tema10/oelmand/wp-json/wp/v2/oeltype?per_page=100";
						const nationalitetUrl = "https://designbymagnus.dk/kea/2_semester/tema10/oelmand/wp-json/wp/v2/nationalitet?per_page=100";
						const bryggeriUrl = "https://designbymagnus.dk/kea/2_semester/tema10/oelmand/wp-json/wp/v2/bryggeri?per_page=100";

						// Henter alt rest api indhold, henter et array fra WP i json
						async function getJSON() {
							// Custom post produkter
							const produktResponce = await fetch(produktUrl);
							produkter = await produktResponce.json();

							// Custom kategorityper
							const oeltypeResponce = await fetch(oeltypeUrl);
							oeltyper = await oeltypeResponce.json();

							const nationalitetResponce = await fetch(nationalitetUrl);
							nationaliteter = await nationalitetResponce.json();

							const bryggeriResponce = await fetch(bryggeriUrl);
							bryggerier = await bryggeriResponce.json();
							
							console.log(produkter);
							console.log(oeltyper);
							console.log(nationaliteter);
							console.log(bryggerier);

							// Kalder funktioner
							filterKnapper();
							visProdukter();
						}

						function filterKnapper (){
							oeltyper.forEach(oeltype =>{
								document.querySelector("#oeltype_filtrering").innerHTML += `<button class="filterOeltype" data-oeltype="${oeltype.id}">${oeltype.name}</button>`
							})

							nationaliteter.forEach(nationalitet =>{
								document.querySelector("#nationalitet_filtrering").innerHTML += `<button class="filterNationalitet" data-nationalitet="${nationalitet.id}">${nationalitet.name}</button>`
							})

							bryggerier.forEach(bryggeri =>{
								document.querySelector("#bryggeri_filtrering").innerHTML += `<button class="filterBryggeri" data-bryggeri="${bryggeri.id}">${bryggeri.name}</button>`
							})

							addEventListenersToKnapper();
						}

						function addEventListenersToKnapper(){
							document.querySelectorAll("#oeltype_filtrering button").forEach(elm =>{
								elm.addEventListener("click", filtreringOeltype);
							});

							document.querySelectorAll("#nationalitet_filtrering button").forEach(elm =>{
								elm.addEventListener("click", filtreringNationalitet);
							});

							document.querySelectorAll("#bryggeri_filtrering button").forEach(elm =>{
								elm.addEventListener("click", filtreringBryggeri);
							});
						}

						function filtreringOeltype(){
							filterOeltype = this.dataset.oeltype;
							console.log(parseInt(filterOeltype));

							document.querySelector(".valgt").classList.remove("valgt");
							this.classList.add("valgt");

							visProdukter();
						}

						function filtreringNationalitet(){
							filterNationalitet = this.dataset.nationalitet;
							console.log(parseInt(filterNationalitet));

							document.querySelector(".valgt").classList.remove("valgt");
							this.classList.add("valgt");

							visProdukter();
						}

						function filtreringBryggeri(){
							filterBryggeri = this.dataset.bryggeri;
							console.log(parseInt(filterBryggeri));

							document.querySelector(".valgt").classList.remove("valgt");
							this.classList.add("valgt");

							visProdukter();
						}

						function visProdukter() {
							const indhold_liste = document.querySelector("#produkt_indhold article");
							const skabelon = document.querySelector("template");

							// console.log(produkter);
							// console.log(oeltyper);
							// console.log(nationaliteter);
							// console.log(bryggerier);

							indhold_liste.textContent = "";
							
							produkter.forEach(produkt => {
								// console.log(produkt.oeltyper);

								if ((filterOeltype == "alle"  || produkt.oeltype.includes(parseInt(filterOeltype))) && (filterNationalitet == "alle"  || produkt.nationalitet.includes(parseInt(filterNationalitet)))
								&& (filterBryggeri == "alle"  || produkt.bryggeri.includes(parseInt(filterBryggeri)))) {
								let klon = skabelon.cloneNode(true).content;

								klon.querySelector(".billede").src = produkt.billede.guid;
								klon.querySelector(".titel").textContent = produkt.navn;
								klon.querySelector(".pris").textContent = produkt.pris + " kr.-";

								klon.querySelector(".billede").addEventListener("click", () => {
								location.href = produkt.link;
								});

								// klon.querySelector(".videre").addEventListener("click", () => {
								// location.href = produkt.link;
								// });

								indhold_liste.appendChild(klon);
								}
							});
						}
						
						getJSON();
					</script>
					<!-- REST API indhold slutter her -->

	</div><!-- #primary -->

<?php if ( astra_page_layout() == 'right-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

<?php get_footer(); ?>