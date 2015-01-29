<?php

namespace Herrera\Version\Tests;

use Herrera\PHPUnit\TestCase;
use Herrera\Version\Parser;

class ParserTest extends TestCase
{
    /**
     * @dataProvider getValidVersions
     */
    public function testToBuilder($expected, $major, $minor, $patch, $preRelease, $build)
    {
        $builder = Parser::toBuilder($expected);

        $this->assertSame($major, $builder->getMajor());
        $this->assertSame($minor, $builder->getMinor());
        $this->assertSame($patch, $builder->getPatch());
        $this->assertSame($preRelease, $builder->getPreRelease());
        $this->assertSame($build, $builder->getBuild());
    }

    /**
     * @dataProvider getValidVersions
     */
    public function testToComponents($expected, $major, $minor, $patch, $preRelease, $build)
    {
        $this->assertSame(
            array(
                Parser::MAJOR => $major,
                Parser::MINOR => $minor,
                Parser::PATCH => $patch,
                Parser::PRE_RELEASE => $preRelease,
                Parser::BUILD => $build
            ),
            Parser::toComponents($expected)
        );
    }

    public function testToComponentsInvalid()
    {
        $this->setExpectedException(
            'Herrera\\Version\\Exception\\InvalidStringRepresentationException',
            'The version string representation "test" is invalid.'
        );

        Parser::toComponents('test');
    }

    /**
     * @dataProvider getValidVersions
     */
    public function testToVersion($expected, $major, $minor, $patch, $preRelease, $build)
    {
        $version = Parser::toVersion($expected);

        $this->assertSame($major, $version->getMajor());
        $this->assertSame($minor, $version->getMinor());
        $this->assertSame($patch, $version->getPatch());
        $this->assertSame($preRelease, $version->getPreRelease());
        $this->assertSame($build, $version->getBuild());
    }

    public function getValidVersions()
    {
        return array(
            array('1.2.3', 1, 2, 3, array(), array()),

            array('1.2.3-pre', 1, 2, 3, array('pre'), array()),
            array('1.2.3--', 1, 2, 3, array('-'), array()),
            array('1.2.3-pre-', 1, 2, 3, array('pre-'), array()),
            array('1.2.3-pre-release', 1, 2, 3, array('pre-release'), array()),

            array('1.2.3+build', 1, 2, 3, array(), array('build')),
            array('1.2.3+-', 1, 2, 3, array(), array('-')),
            array('1.2.3+build-1', 1, 2, 3, array(), array('build-1')),
            array('1.2.3+build-not-pre-release', 1, 2, 3, array(), array('build-not-pre-release')),

            array('1.2.3-pre+build', 1, 2, 3, array('pre'), array('build')),
            array('1.2.3-pre-+build', 1, 2, 3, array('pre-'), array('build')),
            array('1.2.3-pre.1+build.1', 1, 2, 3, array('pre', '1'), array('build', '1')),
            array('1.2.3-pre-release.1+build.1', 1, 2, 3, array('pre-release', '1'), array('build', '1')),
        );
    }
}
