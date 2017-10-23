<?php

// RIGHT TRY CATCH SYNTAX ***************************************************************

class ConversationManager
{
	

	// Récupérer toutes les conversations.
	public function getAllConversations(){

		try{
			$sql = 'SELECT c_id, DATE_FORMAT(c_date, "%d/%m/%Y") AS c_date, 
                    DATE_FORMAT(c_date, "%H:%i:%s") AS c_heure, c_termine, 
                    COUNT(m.m_id) AS c_nbmessages 
                    FROM conversation c 
                    LEFT JOIN message m 
                    ON c.c_id = m.m_conversation_fk 
                    GROUP BY c_id';
			$conversations = array();
			
			if(($datas = SPDO::getInst()->callDatabase($sql)) !== false){

				foreach ($datas as $val) {
					$conversations[] = new Conversation($val);
				}
				return $conversations;
			}
		}
		catch(Exception $e){
			throw new Exception( 'Query Failed', $e);
		}
	}
}