<?php

declare(strict_types=1);

namespace App\Utils;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class LdapUtils
{
    public static function syncUser(User $user): void
    {
        if ($user->ldapguid === null) {
            $ldap_user = null;
        } else {
            $ldap_user = \App\Ldap\User::findBy(env('LDAP_LOCATE_USERS_BY', 'mail'), $user->email);
        }

        $projects = Project::with('users')->get();

        foreach ($projects as $project) {
            if ($project->ldapfilter === null) {
                continue;
            }

	    $member_attr = env('LDAP_UNIQUE_MEMBER_ATTRIBUTE', 'uniquemember');
	    $matches_ldap_filter = false;
	    $connection = \LdapRecord\Container::getDefaultConnection();
	    $query = $connection->query();
	    $results = $query->setDn($project->ldapfilter)->select($member_attr)->get();
	    if (array_key_exists($member_attr, $results[0])) {
	        $matches_ldap_filter = in_array($user->email, $results[0][$member_attr]);
	    }

            $relationship_already_exists = $project->users->contains($user);

            if ($matches_ldap_filter && !$relationship_already_exists) {
                $project->users()->attach($user->id, ['role' => Project::PROJECT_USER]);
                Log::info("Added user $user->email to project $project->name.");
            } elseif (!$matches_ldap_filter && $relationship_already_exists) {
                $project->users()->detach($user->id);
                Log::info("Removed user $user->email from project $project->name.");
            }
        }
    }
}
