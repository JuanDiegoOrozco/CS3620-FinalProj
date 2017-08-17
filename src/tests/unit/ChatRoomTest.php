<?php

require __DIR__.'/../../domain/ChatRoom.php';

class ChatRoomTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected $chatRoom;

    protected function _before()
    {
        $this->chatRoom = new App\domain\ChatRoom();
    }

    protected function _after()
    {
        unset($this->chatRoom);
    }

    // tests
    public function testChatRoomNotNull()
    {
        // Arrange, Act
        $chRm = $this->chatRoom;

        // Assert
        $this->assertTrue($chRm != null);
    }

    public function testChatRoomName()
    {
        // Arrange
        $var = "Super chat";
        $chRm = $this->chatRoom;

        // Act
        $chRm->SetName($var);

        // Assert
        $this->assertEquals($var, $chRm->GetName(), "Required name not found");
    }

    public function testChatRoomSubject()
    {
        // Arrange
        $var = "Cheese";
        $chRm = $this->chatRoom;

        // Act
        $chRm->SetSubject($var);

        // Assert
        $this->assertEquals($var, $chRm->GetSubject(), "Required subject not found");
    }

    public function testChatRoomJoin()
    {
        // Arrange
        $user = new App\domain\User();
        $chRm = $this->chatRoom;
        $user->SetAlias("Juan Orozco");
        $user->SetEmail("juanorozco@gmail.com");

        // Act
        $chRm->Join($user);
        $fUser = $chRm->GetMembers()[0];

        // Assert
        $this->assertTrue($user === $fUser, "User was not found in Chat Room");
        $this->assertEquals($fUser->GetAlias(),"Juan Orozco", "User was not found in Chat Room");
        $this->assertEquals($fUser->GetEmail(), "juanorozco@gmail.com", "User was not found in the Chat Room");
    }

    public function testChatRoomMultiJoin()
    {
        // Arrange
        $user = new App\domain\User();
        $user2 = new App\domain\User();
        $chRm = $this->chatRoom;
        $user->SetAlias("Juan Orozco");
        $user->SetEmail("juanorozco@gmail.com");
        $user2->SetAlias("Mike Jones");
        $user2->SetEmail("mikejones@gmail.com");

        // Act
        $chRm->Join($user);
        $chRm->Join($user2);
        $fUser = $chRm->GetMembers()[0];
        $fUser2 = $chRm->GetMembers()[1];

        // Assert
        $this->assertTrue($user === $fUser, "User was not found in Chat Room");
        $this->assertEquals($fUser->GetAlias(),"Juan Orozco", "User was not found in Chat Room");
        $this->assertEquals($fUser->GetEmail(), "juanorozco@gmail.com", "User was not found in the Chat Room");

        $this->assertTrue($user2 === $fUser2, "User was not found in Chat Room");
        $this->assertEquals($fUser2->GetAlias(),"Mike Jones", "User was not found in Chat Room");
        $this->assertEquals($fUser2->GetEmail(), "mikejones@gmail.com", "User was not found in the Chat Room");

        $this->assertTrue(count($chRm->GetMembers()) == 2, "Required number of users was not found");
    }

    public function testChatRoomLeave()
    {
        // Arrange
        $user = new App\domain\User();
        $chRm = $this->chatRoom;
        $user->SetAlias("Juan Orozco");
        $user->SetEmail("juanorozco@gmail.com");

        // Act
        $varBeforeCount = count($chRm->GetMembers());
        $chRm->Join($user);
        $fUser = $chRm->GetMembers()[0];
        $this->assertTrue($user === $fUser, "User was not found in Chat Room");
        $this->assertEquals($fUser->GetAlias(),"Juan Orozco", "User was not found in Chat Room");
        $this->assertEquals($fUser->GetEmail(), "juanorozco@gmail.com", "User was not found in the Chat Room");
        $varBefore = count($chRm->GetMembers());
        $removed = $chRm->Leave($user);
        $varAfter = count($chRm->GetMembers());

        // Assert
        $this->assertTrue(count($chRm->GetMembers()) == 0, "User was not removed, before count: " .
            $varBeforeCount . " before: ". $varBefore. " after: " . $varAfter . " removed: " . $removed);
    }

    public function testChatRoomLeaveJoin()
    {
        // Arrange
        $user = new App\domain\User();
        $chRm = $this->chatRoom;
        $user->SetAlias("Juan Orozco");
        $user->SetEmail("juanorozco@gmail.com");

        // Act
        $varBeforeCount = count($chRm->GetMembers());
        $chRm->Join($user);
        $fUser = $chRm->GetMembers()[0];
        $this->assertTrue($user === $fUser, "User was not found in Chat Room");
        $this->assertEquals($fUser->GetAlias(),"Juan Orozco", "User was not found in Chat Room");
        $this->assertEquals($fUser->GetEmail(), "juanorozco@gmail.com", "User was not found in the Chat Room");
        $varBefore = count($chRm->GetMembers());
        $removed = $chRm->Leave($user);
        $varAfter = count($chRm->GetMembers());
        $this->assertTrue(count($chRm->GetMembers()) == 0, "User was not removed, before count: " .
            $varBeforeCount . " before: ". $varBefore. " after: " . $varAfter . " removed: " . $removed);
        $chRm->Join($user);

        // Assert
        $this->assertTrue(count($chRm->GetMembers()) == 1,
            "User did not rejoin the ChatRoom, Count: " . count($chRm->GetMembers()));
        $fUser = $chRm->GetMembers()[0];
        $this->assertTrue($user === $fUser, "User was not found in Chat Room");
        $this->assertEquals($fUser->GetAlias(),"Juan Orozco", "User was not found in Chat Room");
        $this->assertEquals($fUser->GetEmail(), "juanorozco@gmail.com", "User was not found in the Chat Room");

    }

    public function testChatRoomMultiLeave()
    {
        // Arrange
        $user = new App\domain\User();
        $user2 = new App\domain\User();
        $user3 = new App\domain\User();

        $chRm = $this->chatRoom;
        $user->SetAlias("Juan Orozco");
        $user->SetEmail("juanorozco@gmail.com");
        $user2->SetAlias("Mike Jones");
        $user2->SetEmail("mikejones@gmail.com");
        $user3->SetAlias("Juan Corona");
        $user3->SetEmail("juancorona@gmail.com");

        // Act
        $varBeforeCount = count($chRm->GetMembers());
        $chRm->Join($user);
        $chRm->Join($user2);
        $chRm->Join($user3);
        $fUser = $chRm->GetMembers()[0];
        $fUser2 = $chRm->GetMembers()[1];
        $fUser3 = $chRm->GetMembers()[2];
        $this->assertTrue($user === $fUser, "User was not found in Chat Room");
        $this->assertEquals($fUser->GetAlias(),"Juan Orozco", "User was not found in Chat Room");
        $this->assertEquals($fUser->GetEmail(), "juanorozco@gmail.com", "User was not found in the Chat Room");
        $this->assertTrue($user2 === $fUser2, "User was not found in Chat Room");
        $this->assertEquals($fUser2->GetAlias(),"Mike Jones", "User was not found in Chat Room");
        $this->assertEquals($fUser2->GetEmail(), "mikejones@gmail.com", "User was not found in the Chat Room");
        $this->assertTrue($user3 === $fUser3, "User was not found in Chat Room");
        $this->assertEquals($fUser3->GetAlias(),"Juan Corona", "User was not found in Chat Room");
        $this->assertEquals($fUser3->GetEmail(), "juancorona@gmail.com", "User was not found in the Chat Room");
        $varBefore = count($chRm->GetMembers());
        $removed = $chRm->Leave($user);
        $this->assertTrue(count($chRm->GetMembers()) == 2, "wrong count");
        $removed2 = $chRm->Leave($user2);
        $varAfter = count($chRm->GetMembers());

        // Assert, There should only be one left
        $this->assertTrue(count($chRm->GetMembers()) == 1, "Users were not removed, before count: " . $varBeforeCount .
            " before: ". $varBefore. " after: " . $varAfter .
            " removed: " . ($removed and $removed2));
    }

    public function testChatRoomMultiLeaveAll()
    {
        // Arrange
        $user = new App\domain\User();
        $user2 = new App\domain\User();
        $user3 = new App\domain\User();

        $chRm = $this->chatRoom;
        $user->SetAlias("Juan Orozco");
        $user->SetEmail("juanorozco@gmail.com");
        $user2->SetAlias("Mike Jones");
        $user2->SetEmail("mikejones@gmail.com");
        $user3->SetAlias("Juan Corona");
        $user3->SetEmail("juancorona@gmail.com");

        // Act
        $varBeforeCount = count($chRm->GetMembers());
        $chRm->Join($user);
        $chRm->Join($user2);
        $chRm->Join($user3);
        $fUser = $chRm->GetMembers()[0];
        $fUser2 = $chRm->GetMembers()[1];
        $fUser3 = $chRm->GetMembers()[2];
        $this->assertTrue($user === $fUser, "User was not found in Chat Room");
        $this->assertEquals($fUser->GetAlias(),"Juan Orozco", "User was not found in Chat Room");
        $this->assertEquals($fUser->GetEmail(), "juanorozco@gmail.com", "User was not found in the Chat Room");
        $this->assertTrue($user2 === $fUser2, "User was not found in Chat Room");
        $this->assertEquals($fUser2->GetAlias(),"Mike Jones", "User was not found in Chat Room");
        $this->assertEquals($fUser2->GetEmail(), "mikejones@gmail.com", "User was not found in the Chat Room");
        $this->assertTrue($user3 === $fUser3, "User was not found in Chat Room");
        $this->assertEquals($fUser3->GetAlias(),"Juan Corona", "User was not found in Chat Room");
        $this->assertEquals($fUser3->GetEmail(), "juancorona@gmail.com", "User was not found in the Chat Room");
        $varBefore = count($chRm->GetMembers());
        $removed = $chRm->Leave($user);
        $this->assertTrue(count($chRm->GetMembers()) == 2, "wrong count");
        $removed2 = $chRm->Leave($user2);
        $varAfter = count($chRm->GetMembers());
        $removed3 = $chRm->Leave($user3);

        // Assert
        $this->assertTrue(count($chRm->GetMembers()) == 0, "Users were not removed, before count: "
            . $varBeforeCount . " before: ". $varBefore. " after: " . $varAfter .
            " removed: " . ($removed and $removed2 and $removed3));
    }

    public function testChatRoomAddMessage()
    {
        // Arrange
        $user = new App\domain\User();
        $user->SetAlias("bob");
        $chRm = $this->chatRoom;
        $message = new App\domain\Message($user,"I like cheese");

        // Act
        $chRm->AddMessage($message);

        // Assert
        $this->assertTrue(count($chRm->GetMessages()) == 1, "Required number of messages not found");
        $this->assertEquals($chRm->GetMessages()[0]->GetSender()->GetAlias(),"bob","The required sender not found");
    }

    public function testChatRoomMessageRemoveAllAddMessage()
    {
        // Arrange
        $user = new App\domain\User();
        $user->SetAlias("bob");
        $chRm = $this->chatRoom;
        $message = new App\domain\Message($user,"I like cheese");

        // Act
        $chRm->AddMessage($message);
        $this->assertTrue(count($chRm->GetMessages()) == 1, "Required number of messages not found");
        $this->assertEquals($chRm->GetMessages()[0]->GetSender()->GetAlias(),"bob",
            "The required sender not found");
        $chRm->RemoveAllMessages();
        $this->assertTrue(count($chRm->GetMessages()) == 0, "Message was not removed");

        // Assert
        $chRm->AddMessage($message);
        $this->assertTrue(count($chRm->GetMessages()) == 1, "Required number of messages not found");
        $this->assertEquals($chRm->GetMessages()[0]->GetSender()->GetAlias(),"bob",
            "The required sender not found");
    }

    public function testChatRoomAddMultiMessage()
    {
        // Arrange
        $user = new App\domain\User();
        $user->SetAlias("bob");
        $chRm = $this->chatRoom;
        $message = new App\domain\Message($user,"I like cheese");
        $message2 = new App\domain\Message($user, "One Ton Stone");

        // Act
        $chRm->AddMessage($message);
        $chRm->AddMessage($message2);

        // Assert
        $this->assertTrue(count($chRm->GetMessages()) == 2, "Required number of messages not found");
        $this->assertEquals($chRm->GetMessages()[0]->GetSender()->GetAlias(),
            "bob","The required sender not found");
        $this->assertEquals($chRm->GetMessages()[1]->GetMessage(),"One Ton Stone","The message not found");
    }

    public function testChatRoomAddMultiMessageRemoveAll()
    {
        // Arrange
        $user = new App\domain\User();
        $user->SetAlias("bob");
        $chRm = $this->chatRoom;
        $message = new App\domain\Message($user,"I like cheese");
        $message2 = new App\domain\Message($user, "One Ton Stone");
        $chRm->AddMessage($message);
        $chRm->AddMessage($message2);

        // Act
        $chRm->RemoveAllMessages();

        // Assert
        $this->assertTrue(count($chRm->GetMessages()) == 0, "All Messages were not removed");
    }
}