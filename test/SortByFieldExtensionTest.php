<?php
/**
 * User: Victor Häggqvist
 * Date: 3/4/15
 * Time: 3:12 AM
 */

namespace Snilius\Twig\Tests;

use Snilius\Twig\SortByFieldExtension;
use Twig_Loader_Array;
use Twig_Environment;

class SortByFieldExtensionTest extends \PHPUnit_Framework_TestCase
{

    public function testExtensionLoad()
    {
        $loader = new Twig_Loader_Array(array('foo'=>''));
        $twig = new Twig_Environment($loader);
        $twig->addExtension(new SortByFieldExtension());
        $this->addToAssertionCount(1);
        $twig->render('foo');
    }

    public function testSortArray()
    {
        $base = array(
        array(
        "name" => "Redmine",
        "desc" => "Issues Tracker",
        "url"  => "http://www.redmine.org/",
        "oss"  => "GPL",
        "cost" => 0
        ),
        array(
        "name" => "GitLab",
        "desc" => "Version Control",
        "url"  => "https://about.gitlab.com/",
        "oss"  => "GPL",
        "cost" => 1,
        ),
        array(
        "name" => "Jenkins",
        "desc" => "Continous Integration",
        "url"  => "http://jenkins-ci.org/",
        "oss"  => "MIT",
        "cost" => 0,
        ),
        array(
        "name" => "Piwik",
        "desc" => "Web Analytics",
        "url"  => "http://piwik.org/",
        "oss"  => "GPL",
        "cost" => 1
        )
        );

        $fact = array('GitLab','Jenkins','Piwik','Redmine');

        $filter = new SortByFieldExtension();
        $sorted = $filter->sortByFieldFilter($base, 'name');

        for ($i = 0; $i < count($fact); $i++) {
            $this->assertEquals($fact[$i], $sorted[$i]['name']);
        }
    }

    public function testSortObjects()
    {
        $base = array();
        $ob1 = new Foo();
        $ob1->name = "Redmine";
        $base[]=$ob1;

        $ob2 = new Foo();
        $ob2->name = "GitLab";
        $base[]=$ob2;

        $ob3 = new Foo();
        $ob3->name = "Jenkins";
        $base[]=$ob3;

        $ob4 = new Foo();
        $ob4->name = "Jenkins";
        $base[]=$ob4;

        $fact = array('GitLab','Jenkins','Jenkins','Redmine');

        $filter = new SortByFieldExtension();
        $sorted = $filter->sortByFieldFilter($base, 'name');

        for ($i = 0; $i < count($fact); $i++) {
            $this->assertEquals($fact[$i], $sorted[$i]->name);
        }
    }

    public function testNonArrayBase()
    {
        $filter = new SortByFieldExtension();
        $this->setExpectedException('InvalidArgumentException');
        $filter->sortByFieldFilter(1, '');
    }

    public function testInvalidField()
    {
        $filter = new SortByFieldExtension();
        $this->setExpectedException('Exception');
        $filter->sortByFieldFilter(array(), null);
    }

    public function testUnknownField()
    {
        $filter = new SortByFieldExtension();
        $this->setExpectedException('Exception');
        $filter->sortByFieldFilter(array(new Foo()), 'bar');
    }
}
