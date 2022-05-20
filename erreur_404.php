<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8"/>
	<title>Page introuvable</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="robots" content="noindex, nofollow"/>
	<meta name="color-scheme" content="light dark"/>
	<meta name="theme-color" content="#f9f9fb" media="(prefers-color-scheme: light)">
	<meta name="theme-color" content="#010101" media="(prefers-color-scheme: dark)">
	<meta name="description" content="Page introuvable"/>
	<link rel="icon" type="image/webp" href="img/meteor_favicon.webp"/>
	<link rel="apple-touch-icon" href="img/meteor_apple_touch.webp">
	<style>
		@font-face {
			font-family: "Open Sans";
			font-display: swap;
			src: url("style/polices/open-sans-600.woff2") format("woff2"),
				url("style/polices/open-sans-600.woff") format("woff");
		}
		body{
			margin: 0;
			font-family: "Open Sans", Arial, sans-serif;
			background: url("img/erreur_404.jpg");
			background-size: cover;
			background-position: center center;
		}
		div{
			display: flex;
			flex-direction: column;
			justify-content: space-between;
			text-align: center;
			height: 100vh;
		}
		p{
			font-size: 34px;
			color: #ffffff;
			margin: 0;
		}
		div::before{
			content: "";
			height: calc(28px + 10px);
		}
		a{
			color: #bfbfbf;
			font-size: 20px;
			line-height: 28px;
			text-transform: uppercase;
			transition: 0.3s;
			text-decoration: none;
			outline: none;
			padding-bottom: 10px;
		}
		a:hover{
			color: #8b8b8b;
		}
		a:active{
			color: #727272;
		}

		/* Version tablette */
		@media only screen and (min-width: 800px) and (max-width: 1080px){
			p{
				font-size: 28px;
			}
			a{
				font-size: 16px;
			}
		}

		/* Version mobile */
		@media screen and (max-width: 799px){
			p{
				font-size: 24px;
			}
			a{
				font-size: 14px;
			}
		}
	</style>
</head>
<body>
	<div>
		<p>
			Erreur 404
			<br>
			Page introuvable
		</p>
		<a href=".">Retour à l'accueil</a>
	</div>
</body>
</html>
