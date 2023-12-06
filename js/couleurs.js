/**
 * Classe pour les couleurs du graphique
 */
class CouleursGraph {
	constructor(bgColor, gridColor, color, lineColor, fillColor) {
		this.bgColor = bgColor;
		this.gridColor = gridColor;
		this.color = color;
		this.lineColor = lineColor;
		this.fillColor = fillColor;
	}

	getCouleursTableau() {
		return Array(
			this.bgColor, this.gridColor,
			this.color, this.lineColor,
			this.fillColor
		);
	}
}

class CouleursClaires extends CouleursGraph {
	constructor() {
		super(
			"#ffffff", "#eeeeee",
			"#404040", "#e7e7e7",
			"rgba(205, 205, 205, 0.2)"
		);
	}
}

class CouleursSombres extends CouleursGraph {
	constructor() {
		super(
			"#000000", "#494949",
			"#bfbfbf", "#4f4f4f",
			"rgba(25, 25, 25, 0.8)"
		);
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
			"#395d7f", "#00bfff", "#00dd00", "#d2691e",
			0.00, 0.35, 0.55, 1.00
		);
	}
}

class CouleursDonneesHum extends CouleursDonnees {
	constructor() {
		super(
			"#d2691e", "#00dd00", "#00bfff", "#395d7f",
			0.00, 0.45, 0.75, 1.00
		);
	}
}