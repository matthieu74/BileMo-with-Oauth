<?php
namespace ClientBundle\Models\Service;

use ClientBundle\Entity\User;
use ClientBundle\Entity\Client;

class ClientService
{
	private $em;
	private $fm;
	public function  __construct($em, $fm)
	{
		$this->em = $em;
		$this->fm = $fm;
	}
	
	public function getAllUsers($user)
	{
		if ($user instanceof User)
		{
			$client = $user->getClient();
			return $this->em->getRepository('ClientBundle:User')->findBy(array('client' => $client), array('username' => 'ASC'));
		}
	}
	
	public function getUser($id)
	{
		return $this->em->getRepository('ClientBundle:User')->find($id);
	}
	
	public function addUser($userToCreate, $encoder, $user)
    {
		if ($user instanceof User)
		{
			$client = $user->getClient();
			$encoded = $encoder->encodePassword($userToCreate, $userToCreate->getPassword());
            $userToCreate->setPassword($encoded);

            $userToCreate->setClient($client);
			
			$this->em->persist($userToCreate);
			$this->em->flush();
			return $userToCreate;
		}
		throw new AccessDeniedException('No token given or token is wrong.');
	}
}