<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Mail\OuratableVerificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable, MustVerifyEmailTrait;

    /**
     * Le nom de la table associée au modèle.
     */
    protected $table = 'users';

    /**
     * Les attributs qui peuvent être remplis massivement.
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'avatar',
        'bio',
        'level',
        'xp',
        'next_level_xp',
        'city',
        'birth_date',
        'specialty',
        'email_verified_at',
    ];

    /**
     * Les attributs qui doivent être cachés pour les tableaux.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les types de colonnes (casting).
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'birth_date' => 'date',
    ];

    /**
     * Relation avec les publications
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    /**
     * Relation avec les commentaires
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    /**
     * Relation avec les likes
     */
    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id', 'id');
    }

    /**
     * Personnalisation de l'envoi d'email de vérification
     */
    public function sendEmailVerificationNotification()
    {
        try {
            $verificationUrl = $this->verificationUrl();
            Mail::to($this->email)->send(new OuratableVerificationMail($this, $verificationUrl));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email vérification: ' . $e->getMessage());
        }
    }

    /**
     * Génération de l'URL de vérification
     */
    protected function verificationUrl()
    {
        return \URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $this->id, 'hash' => sha1($this->getEmailForVerification())]
        );
    }

    /**
     * Récupérer l'initiale du nom pour l'avatar
     */
    public function getInitialAttribute()
    {
        return strtoupper(substr($this->name, 0, 1));
    }

    /**
     * Vérifier si l'utilisateur a un téléphone renseigné
     */
    public function hasPhone()
    {
        return !is_null($this->phone) && $this->phone !== '';
    }

    /**
     * Vérifier si l'utilisateur est admin (si vous avez ce rôle)
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Crédite l'utilisateur en points d'expérience et gère la montée de niveau.
     *
     * L'XP représente la progression à l'intérieur du niveau courant. Lorsqu'elle
     * atteint le seuil (« next_level_xp »), le niveau augmente et le seuil suivant
     * est recalculé (1000 XP par niveau).
     *
     * @param  int  $amount  Points à créditer (peut être négatif, ex. retrait d'un like).
     */
    public function addXp(int $amount): void
    {
        $xp = max(0, (int) ($this->xp ?? 0) + $amount);
        $level = (int) ($this->level ?? 1);
        $threshold = (int) ($this->next_level_xp ?? 1000);

        // Montées de niveau successives tant que le seuil est franchi.
        while ($xp >= $threshold) {
            $xp -= $threshold;
            $level++;
            $threshold = $level * 1000;
        }

        $this->forceFill([
            'xp' => $xp,
            'level' => $level,
            'next_level_xp' => $threshold,
        ])->save();
    }

    /**
     * Formater le numéro de téléphone
     */
    public function getFormattedPhoneAttribute()
    {
        if (!$this->phone) return null;
        
        // Formatage basique du téléphone
        $phone = preg_replace('/[^0-9+]/', '', $this->phone);
        return $phone;
    }
}