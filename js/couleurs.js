/**
 *  Classe pour les couleurs du graphique
 */
class CouleursGraph {
	constructor(bgcolor, gridcolor, color, linecolor) {
		this.bgcolor = bgcolor;
		this.gridcolor = gridcolor;
		this.color = color;
		this.linecolor = linecolor;
	}

	getGridcolor() {
		return this.gridcolor;
	}

	getCouleursTableau() {
		return Array(this.bgcolor, this.gridcolor, this.color, this.linecolor);
	}
}

class CouleursClaires extends CouleursGraph {
	constructor() {
		super("#ffffff", "#eeeeee", "#404040", "#e7e7e7");
	}
}

class CouleursSombres extends CouleursGraph {
	constructor() {
		super("#000000", "#494949", "#bfbfbf", "#4f4f4f");
	}
}

/**
 * Classe pour les couleurs des données
 */
class CouleursDonnees {
	constructor(
		degrade1, degrade2, degrade3, degrade4,
		pourcentage1, pourcentage2, pourcentage3, pourcentage4
	) {
		this.degrade1 = degrade1;
		this.degrade2 = degrade2;
		this.degrade3 = degrade3;
		this.degrade4 = degrade4;

		this.pourcentage1 = pourcentage1;
		this.pourcentage2 = pourcentage2;
		this.pourcentage3 = pourcentage3;
		this.pourcentage4 = pourcentage4;
	}

	getPourcentagesDegrade() {
		return Array(
			this.pourcentage1, this.pourcentage2,
			this.pourcentage3, this.pourcentage4
		);
	}

	getCouleursDegrade() {
		return Array(
			this.degrade1, this.degrade2, this.degrade3, this.degrade4
		);
	}

	getPairesPourcentCouleur() {
		return Array(
			Array(this.pourcentage1, this.degrade1),
			Array(this.pourcentage2, this.degrade2),
			Array(this.pourcentage3, this.degrade3),
			Array(this.pourcentage4, this.degrade4)
		);
	}
}

class CouleursDonneesTemp extends CouleursDonnees {
	constructor() {
		super(
			"#0074ff", "#00e600", "#d1e600", "#fb4d0f",
			0.00, 0.40, 0.65 , 1.00
		);
	}
}

class CouleursDonneesHum extends CouleursDonnees {
	constructor() {
		super(
			"#fb4d0f", "#d1e600", "#00e600", "#0074ff",
			0.00, 0.25, 0.50, 1.00
		);
	}
}