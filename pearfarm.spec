<?php

$description = 'hoptoadapp.com is a service to collect all kinds of "errors" from your applications.

Services_Hoptoad is a wrapper around hoptoadapp.com\'s API (Version 2).

Currently this service wrapper supports web and also cli apps.
';

$spec = Pearfarm_PackageSpec::create(array(Pearfarm_PackageSpec::OPT_BASEDIR => dirname(__FILE__)))
             ->setName('Services_Hoptoad')
             ->setChannel('pearfarm.pearfarm.org')
             ->setSummary('A service wrapper to hoptoadapp.com.')
             ->setDescription($description)
             ->setReleaseVersion('0.0.1')
             ->setReleaseStability('alpha')
             ->setApiVersion('0.0.1')
             ->setApiStability('alpha')
             ->setLicense(Pearfarm_PackageSpec::LICENSE_BSD)
             ->setNotes('Initial release.')
             ->addMaintainer('lead', 'Till Klampaeckel', 'till', 'till@php.net')
             ->addGitFiles();