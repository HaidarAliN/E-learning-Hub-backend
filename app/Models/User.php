<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

        /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }

    public function courses(){
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function notifications(){
        return $this->hasMany(Notification::class, 'sent_to');
    }

    public function quizSubmission(){
        return $this->hasMany(StudentSubmission::class, 'student_id');
    }

    public function enrolledCourses(){
        return $this->belongsToMany(Course::class, 'participants', 'user_id', 'course_id')->withPivot('status');
    }

    public function scopeInstructor($query){
        $type = UserType::where('name','instructor')->first();
        return $query->where('user_type_id',$type->id);
    }
    


}
