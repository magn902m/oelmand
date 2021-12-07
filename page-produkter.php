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

					<section id="filtrering_menu">
						<!-- <h4>Øltype</h4>
						<div id="fold_btn">
							<button id="arrow_down">V</button>
						</div>
						<nav id="luk_sammen" class="hidden">
							<ul id="fold_menu" class="hide">
								<nav id="oeltype_filtrering">
									<button data-oeltype="alle" class="valgt">Alle</button>
								</nav>
							</ul>
						</nav> -->
						<div id="filter_box">
						<h3>Filtrer</h3>
						<div id="fold_btn">
							<button id="arrow_down">➔</button>
						</div>
						</div>
						<nav id="luk_sammen" class="hidden">
							<ul id="fold_menu" class="hide">
								<div>
						<h4>Øltype</h4>
						<nav id="oeltype_filtrering">
							<button data-oeltype="alle" class="valgt">Alle</button>
						</nav>
						</div>

						<div>
						<h4>Nationalitet</h4>
						<nav id="nationalitet_filtrering">
							<button data-nationalitet="alle" class="valgt">Alle</button>
						</nav>
						</div>

						<div>
						<h4>Bryggeri</h4>
						<nav id="bryggeri_filtrering">
							<button data-bryggeri="alle" class="valgt">Alle</button>
						</nav>
						</div>
							</ul>
						</nav>
						<!-- <div>
						<h4>Øltype</h4>
						<nav id="oeltype_filtrering">
							<button data-oeltype="alle" class="valgt">Alle</button>
						</nav>
						</div>

						<div>
						<h4>Nationalitet</h4>
						<nav id="nationalitet_filtrering">
							<button data-nationalitet="alle" class="valgt">Alle</button>
						</nav>
						</div>

						<div>
						<h4>Bryggeri</h4>
						<nav id="bryggeri_filtrering">
							<button data-bryggeri="alle" class="valgt">Alle</button>
						</nav>
						</div> -->
					</section>

					<section id="produkt_indhold">
						<article id="produkt_article"></article>
					</section>
				</main>

				<template>
					<article>
						<img class="billede" src="" alt="">
						<h5 class="titel"></h5>
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

						//Lytter efter om #burger_btn bliver klikket på, som efter vil kører openMenu() functionen.
						document.querySelector("#fold_btn").addEventListener("click", openMenu);

						// Open Menu
						function openMenu() {
						//Her bliver der defineret conste variabler, så koden bliver mere læslig, men også nemmere at arbejde.
						const burgerBtn = document.querySelector("#fold_btn");
						const menu = document.querySelector("#fold_menu");
						const nav = document.querySelector("#luk_sammen");

						//Her bliver EventListener fjernet fra openMenu
						burgerBtn.removeEventListener("click", openMenu);

						//Alle class bliver fjernet.
						burgerBtn.classList = "";
						//Her bliver der tilføjet class open til #burger_btn og #menu.
						burgerBtn.classList = "open";
						menu.classList = "open";
						nav.classList = "ready";

						//Lytter efter om #burger_btn bliver klikket på, som efter vil kører hideMenu() functionen.
						burgerBtn.addEventListener("click", hideMenu);
						}

						// Hide Menu
						function hideMenu() {
						//Her gør vi det samme som i openMenu, dog arbjeder vi med hide istedet for open.
						const burgerBtn = document.querySelector("#fold_btn");
						const menu = document.querySelector("#fold_menu");
						const nav = document.querySelector("#luk_sammen");

						burgerBtn.removeEventListener("click", hideMenu);

						burgerBtn.classList = "";
						burgerBtn.classList = "hide";
						menu.classList = "hide";
						nav.classList = "hidden";
						burgerBtn.addEventListener("click", openMenu);
						}

					</script>
					<!-- REST API indhold slutter her -->

	</div><!-- #primary -->

<?php if ( astra_page_layout() == 'right-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

<?php get_footer(); ?>
