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
	 * Page affichant une erreur
	 *
	 * Cette page affiche un message d'erreur et le journalise si activé, de plus, si c'est une erreur HTTP elle modifie le header en conséquent.
	 *
	 * @package    framework
	 * @subpackage classes/core/responses/pages/errors
	 * @version    05/07/2015
	 * @since      20/03/2015
	 */
	abstract class ErrorPage extends Page {
		/*
		 * CHANGELOG:
		 * 05/07/2015: Meilleure implémentation
		 * 08/06/2015: Changement de l'header seulement s'ils n'ont pas encore été envoyé
		 * 23/05/2015: Suppression de la redefinition du constructeur pour une meilleur compatibilité avec le contrôleur
		 * 20/03/2015: Version initiale
		 */

		/**
		 * Code de l'erreur
		 *
		 * @var mixed
		 */
		protected $errorCode;

		/**
		 * Nom de l'erreur
		 *
		 * @var string
		 */
		protected $errorName;

		/**
		 * Message de l'erreur
		 *
		 * @var string
		 */
		protected $errorMessage = 'An error has occurred.';

		/**
		 * Vérifie si on envoi un fichier à télécharger ou un contenu de type parent
		 *
		 * @return boolean Vrai si on envoi un fichier à télécharger
		 */
		protected function isSpecific() {
			return !empty($this->errorCode) || !empty($this->errorName);
		}

		/**
		 * {@inheritdoc}
		 */
		public function send() {

			// Si c'est une réponse spécifique
			if ($this->isSpecific()) {

				// On construit le titre selon les variables de l'objet
				$this->setTitle(__('Error') . (!empty($this->errorCode) ? ' ' . $this->errorCode : '') . (!empty($this->errorName) ? ' &ndash; ' . __($this->errorName) : ''));

				// Le message d'erreur remplace le contenu
				$this->setContent('<p>' . __($this->errorMessage) . '</p>');

				// Journalisation de l'erreur si activé
				if (ENABLE_LOGS) {

					// Si c'est une erreur HTTP, on crée un fichier à part
					$logger = new Logger('http_errors_' . $this->getStatus());
					$logger->setDate();
					$logger->setIPAddress();
					$logger->setRequest();
					$logger->setReferer();
					$logger->write();
				}
			}

			// On envoi les données de la page
			parent::send();
		}

		/**
		 * Retourne le code de l'erreur
		 *
		 * @return mixed Le code de l'erreur
		 */
		public function getErrorCode() {
			return $this->errorCode;
		}

		/**
		 * Modifie le code de l'erreur
		 *
		 * @param  mixed    $errorCode Le nouveau code de l'erreur
		 * @param  boolean  $isHTTP    Vrai si c'est une erreur HTTP, « FALSE » par défaut
		 * @return ErrorPage           L'instance courante
		 */
		public function setErrorCode($errorCode, $isHTTP = FALSE) {
			$this->errorCode = $errorCode;

			// Si c'est une erreur HTTP, on change le status de l'header aussi, de plus le nom de l'erreur prend le message de ce status
			if ($isHTTP) {
				$this->setStatus($errorCode);
				$this->errorName = $this->getStatusMessage();
			}

			return $this;
		}

		/**
		 * Retourne le nom de l'erreur
		 *
		 * @return string Le nom de l'erreur
		 */
		public function getErrorName() {
			return $this->errorName;
		}

		/**
		 * Modifie le nom de l'erreur
		 *
		 * @param  string   $errorName Le nouveau nom de l'erreur
		 * @return ErrorPage           L'instance courante
		 */
		public function setErrorName($errorName) {
			$this->errorName = $errorName;
			return $this;
		}

		/**
		 * Retourne le message de l'erreur
		 *
		 * @return string Le message de l'erreur
		 */
		public function getErrorMessage() {
			return $this->errorMessage;
		}

		/**
		 * Modifie le message de l'erreur
		 *
		 * @param  string   $errorMessage Le nouveau message de l'erreur
		 * @return ErrorPage              L'instance courante
		 */
		public function setErrorMessage($errorMessage) {
			$this->errorMessage = $errorMessage;
			return $this;
		}
	}
?>