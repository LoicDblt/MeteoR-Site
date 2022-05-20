<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8"/>
	<title>MeteoR - Page non trouvée</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="robots" content="noindex, nofollow"/>
	<meta name="color-scheme" content="light dark"/>
	<meta name="theme-color" content="#f9f9fb" media="(prefers-color-scheme: light)">
	<meta name="theme-color" content="#010101" media="(prefers-color-scheme: dark)">
	<meta name="description" content="Page non trouvée"/>
	<link rel="icon" type="image/webp" href="img/meteor_favicon.webp"/>
	<link rel="apple-touch-icon" href="img/meteor_apple_touch.webp">
	<link rel="stylesheet" type="text/css" href="style/style.css"/>
</head>
<body>
	<p>
		Erreur 404
		<br>
		Page introuvable
	</p>
</body>
<style>
@font-face {
	font-family: "Open Sans";
	font-display: swap;
	src: url("style/polices/open-sans-600.woff2") format("woff2"),
		url("style/polices/open-sans-600.woff") format("woff");
}
body{
	width: 100vw;
	height: 100vh;
	margin: 0;
	font-family: "Open Sans", Arial, sans-serif;
	background: url("img/erreur_404.jpg");
	background-size: cover;
	background-position: center center;
}
p{
	display: flex;
	justify-content: center;
	align-items: center;
	text-align: center;
	color: #ffffff;
	font-size: 34px;
	margin: 0;
	height: 100%;
}

/* Version tablette */
@media only screen and (min-width: 800px) and (max-width: 1080px){
	p{
		font-size: 28px;
	}
}

/* Version mobile */
@media screen and (max-width: 799px){
	p{
		font-size: 24px;
	}
}
</style>
</html>
