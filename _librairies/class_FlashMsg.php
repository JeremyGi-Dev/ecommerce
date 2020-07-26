<?php
/**
 * Classe de gestion de message destinés aux utilisateurs. 
 * Principe de fonctionnement : 
 * 1 - le message est ajouté à la liste des messages stockées en session (methode "add")
 * 2 - le message est récupéré sur la page où l'ont souhaite l'affiché (méthode "getLast")
 * 2 - le message est affiché grâce au code HTML retourné (méthode "getHtmlMsg") 
 */
class FlashMsg {
	public static $msg_types = array('error','danger', 'warning', 'info', 'ok');
	const NAME_IN_SESSION = 'flash_msg';

	/**
	 * Ajoute un message à la liste (en session).
	 * @param string $type    Type de message
	 * @param string $message Le corps du message
	 */
	public static function add($type = '', $message = '') {

		if (session_id() == '')
			session_start();
		
		if (!in_array($type, self::$msg_types)) // paramètre incorrect
			return false;

		if (!isset($_SESSION[ self::NAME_IN_SESSION ])) 
			$_SESSION[ self::NAME_IN_SESSION ] = array();
		
		$_SESSION[ self::NAME_IN_SESSION ][] = array(
			'type' => $type,
			'body' => $message
		);

		return true;
	} // add()

	/**
	 * Récupère tous les messages de la liste ou les messages d'un type donné.
	 * @param  string $type Type de message à récupéré. Si laissé vide, tous les message sont retournés
	 * @return array()      Les messages
	 */
	public static function getAll($type = '') {
		$messages = array();

		if (!empty($type) AND !in_array($type, self::$msg_types)) // paramètre incorrect
			return false;

		if (session_id() == '' OR !isset($_SESSION[ self::NAME_IN_SESSION ]))
			return array();

		if (empty($type)){
			$messages = $_SESSION[ self::NAME_IN_SESSION ];
			$_SESSION[ self::NAME_IN_SESSION ] = array(); // on vide les messages retournés
			return $messages;
		}

		foreach ($_SESSION[ self::NAME_IN_SESSION ] as $flash_msg) {
			if ($flash_msg['type'] === $type)
				$messages[] = $flash_msg;
		}

		return $messages;
	} // getAll()

	/**
	 * Récupère le dernier message ajouté à la liste éventuellement filtré sur un type donné
	 * @param  string $type Type de message à récupéré. Si laissé vide, le dernier message ajouté sera retourné
	 * @return array       Le message choisie
	 */
	public static function getLast($type = '') {
		$message = array();

		if (!empty($type) AND !in_array($type, self::$msg_types)) // paramètre incorrect
			return false;

		if (session_id() == '' OR !isset($_SESSION[ self::NAME_IN_SESSION ]))
			return array();

		if (empty($type)){
			if (NULL === $message = array_pop($_SESSION[ self::NAME_IN_SESSION ]))
				return array(); // si le tableau est vide ou que ce n'est pas un tableau
			return $message;
		}

		foreach ($_SESSION[ self::NAME_IN_SESSION ] as $key => $flash_msg) {
			if ($flash_msg['type'] === $type) {
				$message = $flash_msg;
				unset($_SESSION[ self::NAME_IN_SESSION ][ $key ]);
			}
		}
		
		return $message;
	} // getLast()

	/**
	 * Retourne la représentation HTML du message passé en paramètre
	 * @param  array  $message Le message à afficher
	 * @return string          Code HTML du message.
	 */
	public static function getHtmlMsg($message = array()) {
		if (empty($message) OR !is_array($message))
			return '';

		$fa = '';
		switch ($message['type']){
			case 'danger':
			case 'error':
				$fa = '<i class="fa fa-ban"></i>';
				$flash = 'flash-danger';
				$class_txt = 'text-danger';
				break;	
			case 'warning':
				$fa = '<i class="fa fa-exclamation-triangle"></i>';
				$flash = 'flash-warning';
				$class_txt = 'text-warning';
				break;				
			case 'info':
				$fa = '<i class="fa fa-info-circle"></i>';
				$flash = 'flash-info';
				$class_txt = 'text-info';
				break;
			case 'ok':
				$fa = '<i class="fa fa-check"></i>';
				$flash = 'flash-success';
				$class_txt = 'text-success';
				break;
		}

		$html = '
			<div id="flash_message" class="flash '.$flash.'">
				<p class="'.$class_txt.'">'.$fa.' '.$message['body'].'</p>
				<i title="'._('Fermer').'" class="close fa fa-minus"></i>
			</div>
		';

		return $html;
	} // getHtmlMsg
} // class FlashMsg
?>