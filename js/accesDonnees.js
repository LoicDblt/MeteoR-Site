/**
 * Récupère les données dans la base de données
 * @param nomColonne dans la base de données, des mesures à récupérer
 *
 * @returns les données, ou l'erreur rencontrée
 */
function recupBdd(nomColonne) {
	return new Promise((resolve, reject) => {
		// Champ à envoyer au back, pour indiquer la colonne à récupérer
		let champPost = new FormData();
		champPost.append("nomColonne", nomColonne);

		// Récupère les dates des mesures et les données de la colonne demandée
		fetch("../classes/recupColonnes.php", {
			method: "POST",
			body: champPost
		})
		.then(reponse => {
			reponse.json()
				.then(donnees => {
					resolve(donnees);
				})
				.catch(err => {
					reject(err);
				})
		})
		.catch(err => {
			reject(err);
		})
	})
}


/**
 * Récupère les données de d'absisses et d'ordonnées pour le graphique
 * @param nomColonne dans la base de données, des mesures à récupérer
 *
 * @returns un tableau avec les données d'abscisses et d'ordonnées
 */
function recupAbsOrd(nomColonne) {
	return new Promise(resolve => {
		recupBdd(nomColonne)
		.then(retour => {
			resolve(retour);
		})
		.catch(err => {
			console.log("recupAbsOrd - Erreur récupération données :", err);
		})
	})
}