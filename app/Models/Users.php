<?php

namespace App\Models;

use App\Models\Enums\Role;
use App\Models\Utils\Keys;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Auth\Authorizable;

class Users extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'id', 'login', 'role', 'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    private int $id;

    private string $login;

    private string $password;

    private Role $role;

    private ?int $job;

    private \DateTime $created_at;

    private ?\DateTime $updated_at;

    private ?\DateTime $deleted_at;

    public function __construct(?Users $user = null)
    {
        parent::__construct();
		if ($user !== null) {
			$this->setId($user->getId());
			$this->setLogin($user->getLogin());
			$this->setPassword($user->getPassword());
			$this->setRole($user->getRole());
			$this->setJob($user->getJob());
			$this->setCreated($user->getCreated());
			$this->setUpdated($user->getUpdated());
			$this->setDeleted($user->getDeleted());
		}
        $this->setId( 0 );
        $this->setLogin('');
        $this->setPassword('');
        $this->setRole( new Role( Role::UNDEFINED ) );
        $this->setJob(null);
        $this->setCreated(new \DateTime());
        $this->setUpdated(null);
        $this->setDeleted(null);
    }

    public function setId(int $id): Users {
        $this->id = $id;
        return $this;
    }
    public function getId(): int {
        return $this->id;
    }

    public function setLogin(string $login): Users {
        $this->login = $login;
        return $this;
    }
    public function getLogin(): string {
        return $this->login;
    }

    public function setPassword(string $password): Users {
        $this->password = $password;
        return $this;
    }
    public function getPassword(): string {
        return $this->password;
    }

    public function setRole(Role $role): Users {
        $this->role = $role;
        return $this;
    }
    public function getRole(): Role {
        return $this->role;
    }

    public function setJob(?int $job): Users {
        $this->job = $job;
        return $this;
    }
    public function getJob(): ?int {
        return $this->job;
    }

    public function setCreated(\DateTime $created_at): Users
    {
        $this->created_at = $created_at;
        return $this;
    }
    public function getCreated(): \DateTime {
        return $this->created_at;
    }

    public function setUpdated(?\DateTime $updated_at): Users
    {
        $this->updated_at = $updated_at;
        return $this;
    }
    public function getUpdated(): ?\DateTime {
        return $this->updated_at;
    }

    public function setDeleted(?\DateTime $deleted_at): Users
    {
        $this->deleted_at = $deleted_at;
        return $this;
    }
    public function getDeleted(): ?\DateTime {
        return $this->deleted_at;
    }

    public function toArray(): array {
        return array(
            Keys::DATABASE_ID => $this->getId(),
            Keys::DATABASE_LOGIN => $this->getLogin(),
            Keys::DATABASE_PASSWORD => $this->getPassword(),
            Keys::DATABASE_ROLE => $this->getRole()->__toInt(),
            Keys::DATABASE_JOB => $this->getJob(),
            Keys::DATABASE_CREATED_AT => $this->getCreated()->getTimestamp(),
            Keys::DATABASE_UPDATED_AT => ($this->getUpdated() !== null ? $this->getUpdated()->getTimestamp() : null),
            Keys::DATABASE_DELETED_AT => ($this->getDeleted() !== null ? $this->getDeleted()->getTimestamp() : null)
        );
    }
	
//	protected function fetchAll(string $class): array {
//		return array_filter(
//			array_map(static function (array $array) use ($class) {
//				$myObject = new $class();
//
//			})
//		);
//	}
	
	public static function getAllUsers() {
		
		$user = new Users();
		$reflection = new \ReflectionObject($user);
		var_dump($reflection);
		$result = DB::select('SELECT * FROM users WHERE deleted_at IS NULL ');
		foreach ($result as $r) {
			var_dump(new \ReflectionObject($r));
		}
		var_dump($result);
		
//		$result = DB::setFetchMode()
//		var_dump($result);
	}
	public static function getUserById(int $id) {
		$result = DB::select("SELECT * FROM users WHERE id = $id AND deleted_at IS NULL ");
		foreach ($result as $item) {
			$myUser = new Users($item);
		}
	}
	
	
}
