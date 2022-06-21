<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Auth;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

use function PHPUnit\Framework\isEmpty;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function login(Request $request)
    {   
        $input = $request->all();
  
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        $server = array("dc2.psu.ac.th","dc7.psu.ac.th","dc1.psu.ac.th");
        $basedn = "dc=psu,dc=ac,dc=th";
        $domain = "psu.ac.th";

        $username = $request->input('username');
        $password = $request->input('password');

        // User::create($request->all());

        $ldap = self::authenticate($server,$basedn,$domain,$username,$password);
        // try {
            if($ldap[0]){
                $name = $ldap[1]['description'];
                $email = $ldap[1]['mail'];
                    // dd(
                    //     $ldap[1]['accountname'], //username
                    //     $ldap[1]['personid'], //
                    //     $ldap[1]['citizenid'], //บัตรประชาชน
                    //     $ldap[1]['cn'], //
                    //     $ldap[1]['dn'], //
                    //     $ldap[1]['campus'], //วิทยาเขต
                    //     $ldap[1]['campusid'], //รหัสวิทยาเขต
                    //     $ldap[1]['department'], //หน่วยงาน
                    //     $ldap[1]['departmentid'], //รหัสหน่วยงาน
                    //     $ldap[1]['workdetail'], //สังกัด
                    //     $ldap[1]['positionid'], //
                    //     $ldap[1]['description'], //ชื่อ THA
                    //     $ldap[1]['displayname'], //ชื่อ ENG
                    //     $ldap[1]['detail'], //ตำแหน่ง
                    //     $ldap[1]['title'], //คำนำหน้าชื่อ
                    //     $ldap[1]['titleid'],
                    //     $ldap[1]['firstname'],
                    //     $ldap[1]['lastname'],
                    //     $ldap[1]['sex'],
                    //     $ldap[1]['mail'],
                    //     $ldap[1]['othermail']
                    // );

                    $users = DB::table('users')->where('username', '=', $username)->get();
                    if (User::where('username', '=', $username)->count() > 0) 
                    {
                        // user found
                        // dd($users);
                    }
                    else
                    {
                        DB::insert('insert into users(name, fullname, email, username, password, remember_token, created_at , updated_at , role_id) 
                        values(?,?,?,?,?,?,?,?,?)',array('User', $name, $email, $username, Hash::make($password), null, now(), now(), '2'));
                    }
                    // if(isEmpty($users))
                    // {
                    //     dd($users);
                    //     DB::insert('insert into users(name, fullname, email, username, password, remember_token, created_at , updated_at , role_id) 
                    //     values(?,?,?,?,?,?,?,?,?)',array('User', $name, $email, $username, Hash::make($password), null, now(), now(), '2'));
                    // }
                    // foreach ($users as $user) {
                    //     // dd($user->username);
                    //     if($user->username == null)
                    //     {
                    //         DB::insert('insert into users(name, fullname, email, username, password, remember_token, created_at , updated_at , role_id) 
                    //         values(?,?,?,?,?,?,?,?,?)',array('User', $name, $email, $username, Hash::make($password), null, now(), now(), '2'));
                    //     }
                    // }
    
            }
        // } catch (Throwable $e) {
        //     // report($e);
        //     abort(403, 'Unauthorized action.');
        //     // return false;
        // }


        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if(auth()->attempt(array($fieldType => $input['username'], 'password' => $input['password'])))
        {
            // return redirect()->route('/admin/home');
            return redirect()->intended('/admin/home');
        }else{
            return redirect()->route('login')
                ->with('error','Email-Address And Password Are Wrong.');
        }
          
    }

protected static function authenticate($server,$basedn,$domain,$username,$password)
    {
        // try {
            $auth_status = false;
            $i=0;
            while(($i<count($server))&&($auth_status==false)){
            $ldap = ldap_connect("ldaps://".$server[$i]) or 
            $auth_status = false;
            ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
            ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
            $ldapbind = ldap_bind($ldap, $username."@".$domain,$password);
            if($ldapbind){
            if(empty($password)){
            $auth_status = false;
            }else{
            $result[0] = true;
            //Get User Info
            $result[1] = self::get_user_info($ldap,$basedn,$username);
            }
            }else{
            $result[0] = false;
            }
            ldap_close($ldap);
            $i++;
            }
            return $result;
        // } catch (Throwable $e) {
        //     // report($e);
        //     abort(403, 'Unauthorized action.');
        //     // return false;
        // }

    }
    
    protected static function get_user_info($ldap,$basedn,$username)
    {
        $user['cn'] = "";
        $user['dn'] = "";
        $user['accountname'] = "";
        $user['personid'] = "";
        $user['citizenid'] = "";
        $user['campus'] = "";
        $user['campusid'] = "";
        $user['department'] = "";
        $user['departmentid'] = "";
        $user['workdetail'] = "";
        $user['positionid'] = "";
        $user['description'] = "";
        $user['displayname'] = "";
        $user['detail'] = "";
        $user['title'] = "";
        $user['titleid'] = "";
        $user['firstname'] = "";
        $user['lastname'] = "";
        $user['sex'] = "";
        $user['mail'] = "";
        $user['othermail'] = "";
        $sr=ldap_search($ldap, $basedn, 
        "(&(objectClass=user)(objectCategory=person)(sAMAccountName=".$username."))", 
        array("cn", "dn", "samaccountname", "employeeid", "citizenid", "company",
        "campusid", "department", "departmentid", "physicaldeliveryofficename", "positionid", 
        "description", "displayname", "title", "personaltitle", "personaltitleid", "givenname", 
        "sn", "sex", "userprincipalname","mail"));
        $info = ldap_get_entries($ldap, $sr);

        $user['cn'] = $info[0]["cn"][0];
        $user['dn'] = $info[0]["dn"];
        $user['accountname'] = $info[0]["samaccountname"][0];
        $user['personid'] = $info[0]["employeeid"][0];
        $user['citizenid'] = $info[0]["citizenid"][0];
        $user['campus'] = $info[0]["company"][0];
        $user['campusid'] = $info[0]["campusid"][0];
        $user['department'] = $info[0]["department"][0];
        $user['departmentid'] = $info[0]["departmentid"][0];
        $user['workdetail'] = $info[0]["physicaldeliveryofficename"][0];
        $user['positionid'] = $info[0]["positionid"][0];
        $user['description'] = $info[0]["description"][0];
        $user['displayname'] = $info[0]["displayname"][0];
        $user['detail'] = $info[0]["title"][0];
        $user['title'] = $info[0]["personaltitle"][0];
        $user['titleid'] = $info[0]["personaltitleid"][0];
        $user['firstname'] = $info[0]["givenname"][0];
        $user['lastname'] = $info[0]["sn"][0];
        $user['sex'] = $info[0]["sex"][0];
        $user['mail'] = $info[0]["userprincipalname"][0];
        $user['othermail'] = $info[0]["mail"][0];
        return $user;
    }
}