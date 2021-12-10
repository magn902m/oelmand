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
        <h1></h1>
		<?php astra_content_page_loop(); ?>

		<?php astra_primary_content_bottom(); ?>
		<!-- Astra indhold slutter her -->

		<!-- REST API indhold starter her -->
					
		<section id="main_produkter">
			<section id="produkter_oversigt">
				<section id="filtrering_menu">
					<div id="filter_box">
						<h3>Filtrer</h3>
						<div id="fold_btn">
							<button id="arrow_down"class="rotation">➔</button>
						</div>
					</div>
					<nav id="luk_sammen" class="opened">
						<ul id="fold_menu" class="open">
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
				</section>

				<section id="produkt_indhold">
				</section>
			</section>
		</section>

		<template>
			<article>
				<img class="billede" src="" alt="">
				<h5 class="titel"></h5>
				<p class="pris"></p>
			</article>
		</template>

		<script>
			// ------------------- REST API ------------------- //

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
				// Custom post produkter. 
				// Laver en variable, som venter på at der bliver hente et array.
				const produktResponce = await fetch(produktUrl);
				// Når arrayet er hentet, kommer array ind i variablen produkter.
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

			// Her tager vi vores array og kører igennem et forEach loop, hvor vi laver en knap med navn og data-sæt, for hvert objekt i arrayet.
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

			// Ligesom før, tager vi nu knapperne og sætter en lyter på, som kalder en funktion, der passer til knappen.
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
				// Her tager vi vores filterOeltype og sætter det til data-sæt knappen indholder.
				filterOeltype = this.dataset.oeltype;
				console.log(parseInt(filterOeltype));

				// Classen valgt bliver fjernet, og sæt på den knap, der er klikket på.
				document.querySelector(".valgt").classList.remove("valgt");
				this.classList.add("valgt");

				// Produkter funktion, bliver kaldt.
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

			// I denne funktion, bliver array udskrevet til html, så det bliver synligt.
			function visProdukter() {
				const indhold_liste = document.querySelector("#produkt_indhold");
				const skabelon = document.querySelector("template");

				// console.log(produkter);
				// console.log(oeltyper);
				// console.log(nationaliteter);
				// console.log(bryggerier);

				// Sletter alt indhold
				indhold_liste.textContent = "";
				
				// Kører arrayet med produkter igennem et forEach loop.
				produkter.forEach(produkt => {
					// console.log(produkt.oeltyper);

					// Her bliver der kontrollet hvad filterne indholder. Dette har betydning for, hvad der sker udskrevet i browser. 
					if ((filterOeltype == "alle"  || produkt.oeltype.includes(parseInt(filterOeltype))) 
					&& (filterNationalitet == "alle"  || produkt.nationalitet.includes(parseInt(filterNationalitet)))
					&& (filterBryggeri == "alle"  || produkt.bryggeri.includes(parseInt(filterBryggeri)))) {
					
					// Her sætter vi en variable til at, aktivere skabelonens indhold.
					let klon = skabelon.cloneNode(true).content;

					// Her bliver der valgt, hvor hvilket data, skal hen fra elementerne.
					klon.querySelector(".billede").src = produkt.billede.guid;
					klon.querySelector(".titel").textContent = produkt.navn;
					klon.querySelector(".pris").textContent = produkt.pris + " DKK";

					// Her bliver der tilføjet en lytter, på et billede, som fører hen til single view.
					klon.querySelector(".billede").addEventListener("click", () => {
					location.href = produkt.link;
					});

					// Her bliver alle ting som er klonet udskrevet i browseren.
					indhold_liste.appendChild(klon);
					}
				});
			}
			
			//Her bliver getJSON() kald.
			getJSON();

			// REST API indhold slutter her

			// ------------------- Fold menu JS ------------------- //

			//Lytter efter om #filter_box bliver klikket på, som efter vil kører openMenu() functionen.
			function myFunction(x) {
				if (x.matches) { // If media query matches
					//Her gør vi det samme som i openMenu, dog arbjeder vi med hide istedet for open.
					const filterBox = document.querySelector("#filter_box");
					const menu = document.querySelector("#fold_menu");
					const nav = document.querySelector("#luk_sammen");

					filterBox.removeEventListener("click", hideMenu);

					filterBox.classList = "";
					filterBox.classList = "hide";
					menu.classList = "hide";
					nav.classList = "hidden";
					filterBox.addEventListener("click", openMenu);
					console.log("openMenu");

					const arrowBtn = document.querySelector("#arrow_down");
				
					arrowBtn.classList = "";
					arrowBtn.offsetLeft;
					arrowBtn.classList.add("rotation_tilbage");
				}
					
				else {
					//Her bliver der defineret conste variabler, så koden bliver mere læslig, men også nemmere at arbejde.
					const filterBox = document.querySelector("#filter_box");
					const menu = document.querySelector("#fold_menu");
					const nav = document.querySelector("#luk_sammen");

					//Her bliver EventListener fjernet fra openMenu
					filterBox.removeEventListener("click", openMenu);

					//Alle class bliver fjernet.
					filterBox.classList = "";
					//Her bliver der tilføjet class open til #filter_box og #menu.
					filterBox.classList = "open";
					menu.classList = "open";
					nav.classList = "opened";

					//Lytter efter om #filter_box bliver klikket på, som efter vil kører hideMenu() functionen.
					filterBox.addEventListener("click", hideMenu);
					console.log("hideMenu");

					const arrowBtn = document.querySelector("#arrow_down");
				
					arrowBtn.classList = "";
					arrowBtn.offsetLeft;
					arrowBtn.classList.add("rotation");
				}
			}

			let x = window.matchMedia("(max-width: 706px)")
			myFunction(x) // Call listener function at run time
			x.addListener(myFunction) // Attach listener function on state changes



			// Open Menu
			function openMenu() {
				//Her bliver der defineret conste variabler, så koden bliver mere læslig, men også nemmere at arbejde.
				const filterBox = document.querySelector("#filter_box");
				const menu = document.querySelector("#fold_menu");
				const nav = document.querySelector("#luk_sammen");

				//Her bliver EventListener fjernet fra openMenu
				filterBox.removeEventListener("click", openMenu);

				//Alle class bliver fjernet.
				filterBox.classList = "";
				//Her bliver der tilføjet class open til #filter_box og #menu.
				filterBox.classList = "open";
				menu.classList = "open";
				nav.classList = "opened";

				//Lytter efter om #filter_box bliver klikket på, som efter vil kører hideMenu() functionen.
				filterBox.addEventListener("click", hideMenu);
			}

			// Hide Menu
			function hideMenu() {
				//Her gør vi det samme som i openMenu, dog arbjeder vi med hide istedet for open.
				const filterBox = document.querySelector("#filter_box");
				const menu = document.querySelector("#fold_menu");
				const nav = document.querySelector("#luk_sammen");

				filterBox.removeEventListener("click", hideMenu);

				filterBox.classList = "";
				filterBox.classList = "hide";
				menu.classList = "hide";
				nav.classList = "hidden";
				filterBox.addEventListener("click", openMenu);
			}

			//Lytter efter om #filter_box bliver klikket på, som efter vil kører rotationArrowBtn() functionen.
			document.querySelector("#filter_box").addEventListener("click", rotationArrowBtn);

			function rotationArrowBtn() {
				console.log("rotationArrowBtn");

				const filterBox = document.querySelector("#filter_box");
				const arrowBtn = document.querySelector("#arrow_down");
				
				// Ser om arrow inden holder rotation
				let erRotertet = arrowBtn.classList.contains("rotation");

				// Hvis arrow inden holder rotation, fjerner den classerne, siger til browseren den er klar og tilføjer rotation_tilbage.
				if (erRotertet == true) {
					arrowBtn.classList.remove("rotation");
					arrowBtn.classList.remove("rotation_tilbage");
					arrowBtn.offsetLeft;
					arrowBtn.classList.add("rotation_tilbage");
				} else {
					arrowBtn.classList.remove("rotation_tilbage");
					arrowBtn.classList.remove("rotation");
					arrowBtn.offsetLeft;
					arrowBtn.classList.add("rotation");
				}
			}
		</script>

	</div><!-- #primary -->

<?php if ( astra_page_layout() == 'right-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

<?php get_footer(); ?>
