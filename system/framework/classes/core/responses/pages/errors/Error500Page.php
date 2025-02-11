<?php
	/*
	 * MIT License
	 *
	 * Copyright (c) 2017-2025 Alexis Jehan
	 *
	 * Permission is hereby granted, free of charge, to any person obtaining a copy
	 * of this software and associated documentation files (the "Software"), to deal
	 * in the Software without restriction, including without limitation the rights
	 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	 * copies of the Software, and to permit persons to whom the Software is
	 * furnished to do so, subject to the following conditions:
	 *
	 * The above copyright notice and this permission notice shall be included in all
	 * copies or substantial portions of the Software.
	 *
	 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
	 * SOFTWARE.
	 */

	/**
	 * Page d'erreur 500
	 *
	 * L'erreur 500 se produit lorsque le serveur rencontre une erreur interne, se produit quand la base de données n'est pas accessible avec Amity.
	 *
	 * @package    framework
	 * @subpackage classes/core/responses/pages/errors
	 * @version    12/04/2023
	 * @since      20/03/2015
	 */
	class Error500Page extends ErrorPage {
		/*
		 * CHANGELOG:
		 * 12/04/2023: Amélioration de la compatibilité en mode « CLI »
		 * 05/07/2015: Meilleure implémentation
		 * 20/03/2015: Version initiale
		 */

		/**
		 * {@inheritdoc}
		 */
		public function indexAction() {

			// Si l'erreur a été spécifiée dans l'URL on redirige vers l'accueil
			if (isset($_SERVER['REQUEST_URI']) && FALSE !== strpos($_SERVER['REQUEST_URI'], 'error500')) {
				$this->redirect('home');

			// Sinon on affiche l'erreur
			} else {

				// Code d'erreur
				$this->setErrorCode(500, TRUE);

				// Message d'erreur
				$this->setErrorMessage('The server encountered an error or the database is not accessible.');
			}
		}
	}
?>