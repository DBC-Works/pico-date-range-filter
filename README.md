Pico date range filter plugin
=============================

List up the contents of the specified date range.

## Installation 

Copy the php file in your plugin folder.

## Usage

Add meta variables `FromDate` and `ToDate` to specify the range of date, like this:

    /*
    Title: 2014's posts
    FromDate: 2014-01-01
    ToDate: 2014-12-31
    */

Then you use the Twig variables `{{ pages_filtered_by_date }}` and `{{ latest_date }}`.

## Update History

### 2014-10-02

Optimize.

### 2014-09-21

First release.
