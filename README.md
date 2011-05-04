## About:

Keywordz will take an input string and output comma-delimited (meta) keyword string.

Common and duplicate words will be removed from the output string.

## Requirements:

* ExpressionEngine 2.x
* PHP 4.x or greater

## Required parameters:

* __"string"__
    
    If left empty, the _"default"_ parameter will be used.

* __"default"__
    
    Default is an empty string. Put default keywords here if above _"string"_ is empty.

## Optional parameters:

* __"sort"__
    
    Default value is _“length”_, which will sort keywords by length descending (longest word first); _“alphabetical”_ is the other option, which will sort the keywords alphabetically.

* __"sort"__
    
    Default value is _"descending"; _"ascending"_ is the alternative value.

* __"limit"__
    
    Default value is _"0"_, which will return all of the keywords; Specify a whole number, greater than zero, if you want to limit the number of keywords returned.

## Usage example:

    This: 
    {exp:mah_keywordz delimiter="|" default="foo,bar,baz,bing"}Say hello to the most flexible web publishing system you'll ever meet. ExpressionEngine is a flexible, feature-rich content management system that empowers thousands of individuals, organizations, and companies around the world to easily manage their website. If you're tired of the limitations of your current CMS then take ExpressionEngine for a spin...{/exp:mah_keywordz}
    Returns: 
    > expressionengine|organizations|limitations|individuals|publishing|management|companies|thousands|flexible|empowers|content|website|feature|current|easily|system|manage|tired|world|spin|meet|rich|web|cms

    This: 
    {exp:mah_keywordz sort="alphabetical" order="ascending" limit="5" string="Say hello to the most flexible web publishing system you'll ever meet. ExpressionEngine is a flexible, feature-rich content management system that empowers thousands of individuals, organizations, and companies around the world to easily manage their website. If you're tired of the limitations of your current CMS then take ExpressionEngine for a spin..." default="foo,bar,baz,bing"}
    Returns: 
    > world,website,web,tired,thousands

## TODO:

Make admin interface so users can add/edit/change the word exclude list.

## Changelog:

* v2.1: __2011/05/03__
	* Added new parameter "delimiter": This is the delimiter that seperates the keywords. Default is comma.
	* Renamed "option" paramter to "sort". Now, if you want to sort, just specify a sort of "length" (default) or "alphabetical".
* v2.0: __2010/04/14__
	* Updated plugin to be 2.0 compatible and uploaded to github.com.
* v1.0: __2009/09/06__
	* Initial public release.
	* [ExpressionEngine forum thread](http://expressionengine.com/forums/viewthread/128406/)