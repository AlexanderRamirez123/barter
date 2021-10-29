<?PHP

require __DIR__ . "/Channel.php";
require __DIR__ . "/User.php";

class ChannelController
{
    private $conn;

    public function __construct($host, $db, $user, $pass, $port, $charset = "utf8mb4")
    {
        $options = [
            \PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];
    
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port"; 
    
        try 
        {               
            $pdo = new PDO($dsn, $user, $pass, $options);
            $this->conn = $pdo;
        } 
        catch (\PDOException $ex) 
        {
            throw $ex;
        }  
    }

    public function Create($channel_name, $users) : Channel
    {
        try 
        {                        
            // CREO EL CHANNEL
            $datetime = date("Y-m-d H:i:s");     

            $channel_id = $this->Insert("insert into channels(channel_name, datetime) values(:channel_name, :datetime);", [ 
                "channel_name" => $channel_name,
                "datetime" => $datetime
            ]);

            $channel = new Channel();
            $channel->channel_id = $channel_id;
            $channel->channel_name = $channel_name;
            $channel->datetime = $datetime;

            // POR CADA USUARIO CREO UNO
            foreach ($users as $user) 
            {
                $theUser = $this->FindUser($user["user_id"]);
                if($theUser == null)
                {
                    $newUser = new User();
                    $newUser->user_external_id = $user["user_id"];
                    $newUser->user_name = $user["user_name"];

                    $theUser = $this->CreateUser($newUser);
                }

                $this->AddUserToChannel($theUser->user_id, $channel->channel_id);

                $channel->users[] = $theUser;
            } 

            return $channel;
        } 
        catch (\Exception $ex) 
        {
            throw $ex;
        }
    }

    private function Select(String $sql, Array $args = null)
    {
        try 
        {
            $q = $this->conn->prepare($sql);

            if($args != null)
                $q->execute($args);
            else
                $q->execute();

            $result = [];

            while ($row = $q->fetch()) 
                $result[] = $row;

            return $result;
        } 
        catch (\Exception $ex) 
        {
            throw $ex;
        }
    }

    private function Insert(String $sql, Array $data, bool $returnID = true)
    {
        try 
        {
            $q = $this->conn->prepare($sql);
            $q->execute($data);

            if($returnID)
            {
                $res = $this->Select("select last_insert_id() ID;");
                $newID = $res[0]["ID"];

                return $newID;                
            }
        }
        catch (\Exception $ex) 
        {
            throw $ex;
        }
    }

    private function AddUserToChannel($user_id, $channel_id)
    {
        try 
        {            
            $this->Insert("insert into channel_user(user_id, channel_id) values(:user_id, :channel_id)", [ 
                "user_id" => $user_id,
                "channel_id" => $channel_id
            ], false);
        } 
        catch (\Exception $ex) 
        {            
            echo "Error en la creación de la relación.\n";
            throw $ex;
        }
    }

    private function CreateUser(User $user)
    {
        try 
        {            
            $user->user_id = $this->Insert("insert into users(user_external_id, user_name) values(:user_external_id, :user_name);", [ 
                "user_external_id" => $user->user_external_id,
                "user_name" => $user->user_name
            ]);

            return $user;
        } 
        catch (\Exception $ex) 
        {
            echo "Error en la creación del usuario.\n";
            throw $ex;
        }
    }

    private function FindUser($user_id)
    {
        try 
        {            
            $res = $this->Select("select * from users where user_external_id = :user_external_id ;", [ 
                "user_external_id" => $user_id,
            ]);

            if(count($res))
            {              
                $user = new User();  
                $user->user_id = $res[0]["user_id"];
                $user->user_name = $res[0]["user_name"];
                $user->user_external_id = $res[0]["user_external_id"];

                return $user;
            }

            return null;
        } 
        catch (\Exception $ex) 
        {
            throw $ex;
        }
    }

}