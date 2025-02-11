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
	 * Image générée en PHP
	 *
	 * Une image PNG peut être générée en PHP selon un contrôleur. Cette classe nécessite l'extension GD2.
	 *
	 * @package    framework
	 * @subpackage classes/core/responses
	 * @version    26/06/2015
	 * @since      14/05/2015
	 */
	abstract class Image extends Response {
		/*
		 * CHANGELOG:
		 * 26/06/2015: Meilleure implémentation
		 * 14/05/2015: Version initiale
		 */

		/**
		 * Image générée
		 *
		 * @var resource
		 */
		protected $image;

		/**
		 * Vérifie si on envoi une image ou un contenu de type parent
		 *
		 * @return boolean Vrai si on envoi une image
		 */
		protected function isSpecific() {
			return !empty($this->image);
		}

		/**
		 * {@inheritdoc}
		 */
		public function send() {

			// Si c'est une réponse spécifique, c'est-à-dire une image
			if ($this->isSpecific()) {

				// Le contenu est une image PNG
				$this->setHeader('Content-type', 'image/png');

				// On envoi les headers personnalisés
				$this->sendHeaders();

				// On envoit le contenu de l'image générée
				imagepng($this->image);

				// On libère la mémoire utilisée par l'image générée
				imagedestroy($this->image);

			// Sinon on envoi le contenu par défaut
			} else {
				parent::send();
			}
		}

		/**
		 * Retourne l'image générée
		 *
		 * @return resource L'image générée
		 */
		public function getImage() {
			return $this->image;
		}

		/**
		 * Modifie l'image générée
		 *
		 * @param  resource $image La nouvelle image générée
		 * @return Image           L'instance courante
		 */
		public function setImage($image) {

			// L'image doit être une ressource
			if ('gd' !== get_resource_type($image)) {
				throw new InvalidParameterException('The image "%s" is not a valid resource, it must be a "gd" resource', get_resource_type($image));
			}

			$this->image = $image;
			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public static function getTypeName() {
			return get_class();
		}
	}

	// On vérifie que l'extension est disponible
	if (!extension_loaded('gd')) {
		throw new SystemException('"%s" extension is not available', 'gd');
	}
?>