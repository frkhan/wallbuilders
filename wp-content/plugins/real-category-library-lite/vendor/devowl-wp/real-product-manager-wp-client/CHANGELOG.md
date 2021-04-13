# Change Log

All notable changes to this project will be documented in this file.
See [Conventional Commits](https://conventionalcommits.org) for commit guidelines.

## 1.6.1 (2021-03-30)


### chore

* update text to explain the installation type (CU-g57mdw)


### fix

* group licenses by hostname of each blog instead of blog (CU-g751j8)


### refactor

* use composer autoload to setup constants and package localization





# 1.6.0 (2021-03-23)


### chore

* max. activations per license explain in more detail when limit reached and link to customer center (CU-fn1k7v)


### feat

* allow to migrate from older license keys (CU-fq1kd8)


### fix

* allow to only get the href for the plugin activation link and make API public (CU-fq1kd8)
* consider allow autoupdates as true if previously no auto updates exist (CU-fq1kd8)
* do not deactivate license when saving new URL through General > Settings (CU-g150eg)
* in a multisite installation only consider blogs with Real Cookie Banner active (CU-fyzukg)
* plugin could not be installed if an older version of PuC is used by another plugin
* prefill code from warning / error hint and allow 32 char (non-UUID) format codes (CU-fq1kd8)
* switch to blog while validating new hostname for license (CU-fyzukg)





## 1.5.5 (2021-03-10)


### chore

* hide some notices on try.devowl.io (CU-f53trz)
* update texts (CU-f134wh)


### fix

* automatically deactivate license when migrating / cloning the website and show notice (CU-f134wh)





## 1.5.4 (2021-03-02)


### chore

* highlight "Skip & Deactivate" button in feedback form when deactivating plugin (CU-ewzae8)


### fix

* filter duplicates in deactivation feedback and show error message (CU-ewzae8)
* filter spam deactivation feedback by length, word count and email address MX record (CU-ewzae8)
* use site url instead of home url for activating a license (CU-f134wh)
* use whitespace and refactor coding (review 1, CU-ewzae8)





## 1.5.3 (2021-02-24)


### chore

* drop moment bundle where not needed (CU-e94pnh)





## 1.5.2 (2021-02-16)


### fix

* warning (PHP) when previously no autoupdates exist





## 1.5.1 (2021-02-02)


### chore

* hotfix remove function which does not exist in < WordPress 5.5





# 1.5.0 (2021-02-02)


### feat

* introduce new checkbox to enable automatic minor and patch updates (CU-dcyf6c)





## 1.4.5 (2021-01-24)


### fix

* avoid duplicate feedback modals if other plugins of us are active (e.g. RML, CU-cx0ynw)





## 1.4.4 (2021-01-11)


### build

* reduce javascript bundle size by using babel runtime correctly with webpack / babel-loader


### chore

* **release :** publish [ci skip]





## 1.4.3 (2020-12-09)


### chore

* update to webpack v5 (CU-4akvz6)
* updates typings and min. Node.js and Yarn version (CU-9rq9c7)


### fix

* add hint for installation type for better explanation (CU-b8t6qf)





## 1.4.2 (2020-12-01)


### chore

* update dependencies (CU-3cj43t)
* update to composer v2 (CU-4akvjg)


### refactor

* enforce explicit-member-accessibility (CU-a6w5bv)





## 1.4.1 (2020-11-26)


### chore

* **release :** publish [ci skip]


### fix

* show link to account page when max license usage reached (CU-aq0g1g)





# 1.4.0 (2020-11-24)


### feat

* add hasInteractedWithFormOnce property of current blog to REST response (CU-agzcrp)


### fix

* license form was not localized to german (CU-agzcrp)
* use no-store caching for WP REST API calls to avoid issues with browsers and CloudFlare (CU-agzcrp)





## 1.3.4 (2020-11-19)


### fix

* deactivation feedback wrong REST route





## 1.3.3 (2020-11-18)


### fix

* deactivation feedback modal





## 1.3.2 (2020-11-17)


### fix

* duplicate error messages (#acypm6)





## 1.3.1 (2020-11-17)


### fix

* correctly show multisite blogname (#acwzpy)





# 1.3.0 (2020-11-03)


### feat

* allow to disable announcements (#9jwehz)
* translation (#8mrn5a)





# 1.2.0 (2020-10-23)


### feat

* route PATCH PaddleIncompleteOrder (#8ywfdu)


### fix

* typing


### refactor

* use "import type" instead of "import"





# 1.1.0 (2020-10-16)


### build

* use node modules cache more aggressively in CI (#4akvz6)


### chore

* introduce Real Product Manager WordPress client package (#8cxk67)
* update PUC (#8cxk67)
* update PUC (#8cxk67)


### feat

* add checklist in config page header (#8cxk67)
* announcements (#8cxk67)
* introduce feedback modal (#8cxk67)


### fix

* enable old auto updater instead of new one for EA (#8cxk67)
* review 1 (#8cxk67)
* review 2 (#8cxk67)
* review 3 (#8cxk67)
* review 4 (#8cxk67)
* validate response in PUC (#8cxk67)





# 1.1.0 (2020-10-16)


### build

* use node modules cache more aggressively in CI (#4akvz6)


### chore

* introduce Real Product Manager WordPress client package (#8cxk67)
* update PUC (#8cxk67)
* update PUC (#8cxk67)


### feat

* add checklist in config page header (#8cxk67)
* announcements (#8cxk67)
* introduce feedback modal (#8cxk67)


### fix

* enable old auto updater instead of new one for EA (#8cxk67)
* review 1 (#8cxk67)
* review 2 (#8cxk67)
* review 3 (#8cxk67)
* review 4 (#8cxk67)
* validate response in PUC (#8cxk67)
