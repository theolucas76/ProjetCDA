<?php

namespace App\Models;

use App\Models\Enums\Job;
use App\Models\Enums\Role;
use App\Models\Utils\Functions;
use App\Models\Utils\Keys;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Auth\Authorizable;

/**
 * @OA\Schema (
 *     schema="Users",
 *     description="Users Model"
 * )
 */

class Users extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'login', 'role','job', 'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @OA\Property
     * @var int
     */
    private int $id;

    /**
     * @OA\Property
     * @var string
     */
    private string $login;

    /**
     * @OA\Property
     * @var string
     */
    private string $password;

    /**
     * @OA\Property
     * @var Role
     */
    private Role $role;

    /**
     * @OA\Property
     * @var Job
     */
    private Job $job;

    /**
     * @OA\Property
     * @var \DateTime
     */
    private \DateTime $created_at;

    /**
     * @OA\Property
     * @var \DateTime|null
     */
    private ?\DateTime $updated_at;

    /**
     * @OA\Property
     * @var \DateTime|null
     */
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
        $this->setId(0);
        $this->setLogin('');
        $this->setPassword('');
        $this->setRole(new Role(Role::UNDEFINED));
        $this->setJob(new Job(Job::UNDEFINED));
        $this->setCreated(new \DateTime());
        $this->setUpdated(null);
        $this->setDeleted(null);
    }

    public function setId(int $id): Users
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setLogin(string $login): Users
    {
        $this->login = $login;
        return $this;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setPassword(string $password): Users
    {
        $this->password = $password;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setRole(Role $role): Users
    {
        $this->role = $role;
        return $this;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function setJob(Job $job): Users
    {
        $this->job = $job;
        return $this;
    }

    public function getJob(): Job
    {
        return $this->job;
    }

    public function setCreated(\DateTime $created_at): Users
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getCreated(): \DateTime
    {
        return $this->created_at;
    }

    public function setUpdated(?\DateTime $updated_at): Users
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function getUpdated(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function setDeleted(?\DateTime $deleted_at): Users
    {
        $this->deleted_at = $deleted_at;
        return $this;
    }

    public function getDeleted(): ?\DateTime
    {
        return $this->deleted_at;
    }

    public function toArray(): array
    {
        return array(
            Keys::DATABASE_ID => $this->getId(),
            Keys::DATABASE_LOGIN => $this->getLogin(),
            Keys::DATABASE_PASSWORD => $this->getPassword(),
            Keys::DATABASE_ROLE => $this->getRole()->__toInt(),
            Keys::DATABASE_JOB => $this->getJob()->__toInt(),
            Keys::DATABASE_CREATED_AT => $this->getCreated()->getTimestamp(),
            Keys::DATABASE_UPDATED_AT => ($this->getUpdated() !== null ? $this->getUpdated()->getTimestamp() : null),
            Keys::DATABASE_DELETED_AT => ($this->getDeleted() !== null ? $this->getDeleted()->getTimestamp() : null)
        );
    }

    public function fromDatabase(array $array): void
    {
        $this->setId($array[Keys::DATABASE_ID]);
        $this->setLogin($array[Keys::DATABASE_LOGIN]);
        $this->setRole(Role::get($array[Keys::DATABASE_ROLE]));
        $this->setJob(Job::get($array[Keys::DATABASE_JOB]));
        $this->setPassword($array[Keys::DATABASE_PASSWORD]);
        $this->setCreated(Functions::fromUnix($array[Keys::DATABASE_CREATED_AT]));
        $this->setUpdated(($array[Keys::DATABASE_UPDATED_AT] !== null ? Functions::fromUnix($array[Keys::DATABASE_UPDATED_AT]) : null));
        $this->setDeleted(($array[Keys::DATABASE_DELETED_AT] !== null ? Functions::fromUnix($array[Keys::DATABASE_DELETED_AT]) : null));
    }


    /**
     *
     * @return array
     */

    public static function getUsers(): array
    {
        $myUsers = [];
        $result = DB::select('SELECT * FROM users WHERE deleted_at IS NULL ');
        foreach ($result as $item) {
            $user = new Users();
            $user->fromDatabase(json_decode(json_encode($item), true));
            $myUsers[] = $user;
        }
        return $myUsers;
    }

    public static function getUserById(int $id): ?Users
    {
        $myUser = new Users();
        $result = DB::select("SELECT * FROM users WHERE id = $id AND deleted_at IS NULL ");

        if (count($result) > 0) {
            foreach ($result as $item) {
                $myUser->fromDatabase(json_decode(json_encode($item), true));
            }
            return $myUser;
        }
        return null;
    }

    public static function getUserByLogin(string $login): ?Users {
        $myUser = new Users();
        $myResult = DB::select("SELECT * FROM users WHERE login = '$login'");

        if (count($myResult) > 0) {
            foreach ( $myResult as $item ) {
                $myUser->fromDatabase(json_decode(json_encode($item), true));
            }
            return $myUser;
        }
        return null;
    }

    public static function getUsersByRole(int $role): array
    {
        $myUsers = [];
        $myResult = DB::select("SELECT * FROM users WHERE role = $role AND deleted_at IS NULL");

        foreach ($myResult as $item) {
            $user = new Users();
            $user->fromDatabase(json_decode(json_encode($item), true));
            $myUsers[] = $user;
        }
        return $myUsers;
    }

    public static function getUsersByJob(int $job): array
    {
        $myUsers = [];
        $myResult = DB::select("SELECT * FROM users WHERE job = $job");

        foreach ($myResult as $item) {
            $user = new Users();
            $user->fromDatabase(json_decode(json_encode($item), true));
            $myUsers[] = $user;
        }
        return $myUsers;
    }

    /**
     * @OA\Schema(
     *     schema="RegisterRequest",
     *     required={"login", "password", "role", "job"},
     *     @OA\Property (
     *          property="login",
     *          type="string",
     *          default="test12345",
     *          description="Login of the user"
     *     ),
     *     @OA\Property(
     *          property="password",
     *          type="string",
     *          default="Test12345",
     *          description="Password user"
     *     ),
     *     @OA\Property(
     *          property="role",
     *          type="integer",
     *          default=1,
     *          description="User's role"
     *      ),
     *     @OA\Property(
     *          property="job",
     *          type="integer",
     *          default=0,
     *          description="User's job"
     *     )
     * )
     *
     *
     * @param Users $user
     * @return bool
     */

    public static function addUser(Users $user): bool
    {
        return DB::table('users')->insert($user->toArray());
    }

    /**
     *  * @OA\Schema(
     *     schema="PutLoginPasswordRequest",
     *     required={"login", "password", "role", "job"},
     *     @OA\Property (
     *          property="login",
     *          type="string",
     *          default="test12345",
     *          description="Login of the user"
     *     ),
     *     @OA\Property(
     *          property="password",
     *          type="string",
     *          default="Test12345",
     *          description="Password user"
     *     )
     * )
     * @param Users $user
     * @return bool
     */

    public static function updateUser(Users $user): bool
    {
        $user->setUpdated(new \DateTime());
        return DB::table('users')->where('id', $user->getId())->update($user->toArray());
    }

    public static function deleteUser(Users $user): bool
    {
        $user->setDeleted(new \DateTime());
        return DB::table('users')->where('id', $user->getId())
            ->where('deleted_at', null)
            ->update($user->toArray());
    }
}
