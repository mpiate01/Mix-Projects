<?php

//  SE QUALCOSA NON FUNZIA....ho cambiato setQuery() method, prima era solo query, magari non gli ho cambiati tutti
//										  _affectedRowQuery, prima era solo _count      e getAffectedRowQuery()	, prima era affectedRowQuery()


//singletton pattern per getInstance, cosi ci ci connette al database solo una volta
class DB 
{
	private static $_instance = null; //col static si puo accedere senza fare new Class()
	private $_pdo, 
			$_query, 
			$_error =false, //error nella query
			$_results, 		//result set
			$_affectedRowQuery = 0;	//quanti risultati ottenuti

	private function __construct()
	{
		//code standard per PDO
		try {
			//questo sarebbe il dsn da cambiare se non si usa MySql
			$this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
		} 
		catch (PDOException $e)
		{
			die($e->getMessage());   
		}
	}
	//singletton pattern 
	public static function getInstance()
	{
		if(!isset(self::$_instance))
		{
			self::$_instance = new DB();
		}
		return self::$_instance;
	}

	public function setQuery($sql, $params=array())
	{
		$this->_error = false; //RESET ERROR TO FALSE to avoid showing errors for previous queries
		//qui la query viene preparata
		if($this->_query = $this->_pdo->prepare($sql))
		{
			$position = 1;
			if (count($params))
			{	
				foreach ($params as $param)
				{	
					//in pratica questo viene fatto perche puoi associare valori alle ? che ci potrennero essere nella query ( esempio WHERE username = ? , array('ciao'))
					//qui si legge l array di valori e si associano alle varie ?, partendo dal valore 1 della variabile $position
					$this->_query->bindValue($position, $param);
					$position++;
				}
			}
			//qui la query viene executed
			if ($this->_query->execute())
			{
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);  //RESTITUENDO un object, si puo accedere ai vari 
																			//values facendo:  database con query-> queryRisultato-> nomeColonna

				//PER USARE FETCH_OBJ ====   $result[posizione]->nome_colonna;
				//$this->_affectedRowQuery = $this->_query->rowCount(); cambiato introducendo setAffectedRowQuery
				$this->setAffectedRowQuery($this->_query->rowCount());

				//rowCount() non e' portable, per esempio non funziona con database dsn SQLite
				//per renderlo portable bisognerebbe contare con una query le righe uscite
				//$this->_count = $db->query('SELECT COUNT(*) FROM table WHERE data');
				//$this->setAffectedRowQuery($this->_count->fetchColumn());
				/*********il codice sopra restituisce quante righe totali**********/

				// si potrebbe altrimenti controllare if ($this->results dalla query->fetch() )
				// se ha risultati allora prosegue, altrimenti da errori
				// per poi continuare ad esaminare i seguenti risultati bisogna utilizzare un DO WHILE loop
				//perche' si sta gia visualizzando il primo
				/**********pdo video folder 02_06**************/


				// forse pure $affectedRow = $db->exec($query)  da usare non con select
				/**********pdo video folder 02_07**************/

			}
			else
			{
				$this->_error = true;  // questo errore viene dato se ci sono errori nel formulare la query, non riguardo al risultato
			}
		}
		return $this;
	}

	//USATO PER CONTROLLARE SE LA QUERY DA RISULTATO
	public function getAffectedRowQuery()  //count() per il tipo
	{
		return $this->_affectedRowQuery;
	}
	public function setAffectedRowQuery($num)  //count() per il tipo
	{
		$this->_affectedRowQuery = $num;
	}

	public function getError()
	{
		return $this->_error;
	}

	//TO SHOW i risultati della query
	public function getResultsQuery()
	{
		return $this->_results;
	}

	public function firstResult()
	{
		return $this->getResultsQuery()[0];
	}


	/*
	*	QUESTI FORSE SONO OPZIONALI
	*/

	//Method utilizzato per formulare queries, invece di scrivere ogni volta SELECT xxx FROM xxx WHERE
	//si scrivera' solo get('users', array('username', '=' , 'alex'))
	public function action($action, $table, $where = array())
	{
		if(count($where) === 3)   //3 perche' we need a 'field', 'operator','value'
		{
			$operators = array('=','>','<','>=','<=');

			$field = $where[0];
			$operator = $where[1];
			$value = $where[2];

			if (in_array($operator, $operators))
			{
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ? ";//"SELECT * FROM users WHERE username = 'Alex'"
				//il $value viene associato quando si usa il method query()
				//se la query non da errore allora return $this
				if (!$this->setQuery($sql, array($value))->getError()) 
				{
					return $this;
				}
			}
		}
		return false;
	}

	//METHOD PER VELOCIZZARE LA QUERY SELECT ALL
	public function getQuery($table, $where)
	{
		return $this->action('SELECT *', $table, $where);
	}
	//METHOD PER VELOCIZZARE LA QUERY DELETE ALL
	public function deleteQuery($table, $where)
	{
		return $this->action('DELETE', $table, $where);		
	}

	//UPDATE QUERY prendendo come riferimento id 
	public function updateQuery($table,$id,$fields)
	{
		if(count($fields))
		{
			$set = '';
			$position = 1;
			//dove name is the key of the array = nome colonna    ESEMPIO  password = ?
			//value viene usato solo per contare il numero delle ? da mettere
			//i veri valori vengono inseriti dopo nel !$this->setQuery($sql,$fields)->getError()
			foreach ($fields as $name => $value)
			{
				$set .= "{$name} = ?";
				if ($position < count($fields))		//usato per mettere virgole nel caso
				{									//si vogliano aggiornare piu fields
					$set .= ', ';
				}
				$position++;
			}

			//$sql = "UPDATE users SET password = 'newpassword' WHERE id = 2";
			$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

			if(!$this->setQuery($sql,$fields)->getError())
			{
				return true;
			}
		}
		return false;
	}

	public function insertQuery($table, $fields = array())
	{
		if(count($fields))
		{
			$keys = array_keys($fields);
			$values = '';   //per le ?
			$position = 1;

			//il foreach serve per mettere le ?, IF serve per mettere le virgole tra le ? ma non all ultima
			foreach ($fields as $field)
			{
				$values .= '?';
				//are we the end of the field that we defined
				if ($position < count($fields))
				{
					$values .=	', ';
				}
				$position++;
			}
			//$sql = "INSERT INTO users ('username', 'password', 'salt') VALUES ('Porco','pass','salt') ";
			//i 3 campi vengo presi dalle keys dell'array ed inseriti in un'unica string con
			//implode, ogni campo e' separato da =  `, `
			$sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";
			//cosi la query risulta =  INSERT INTO users (`username`,`password`,`salt`) VALUES (?,?,?)
			if (!$this->setQuery($sql, $fields)->getError())
			{
				return true;
			}
		}
		return false;
	}


}