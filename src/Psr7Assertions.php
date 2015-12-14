<?php
namespace Helmich\Psr7Assert;


use Helmich\JsonAssert\Constraint\JsonValueMatchesMany;
use Helmich\Psr7Assert\Constraint\BodyMatchesConstraint;
use Helmich\Psr7Assert\Constraint\HasHeaderConstraint;
use Helmich\Psr7Assert\Constraint\HasMethodConstraint;
use Helmich\Psr7Assert\Constraint\HasUriConstraint;
use PHPUnit_Framework_Assert as Assert;
use PHPUnit_Framework_Constraint as Constraint;
use PHPUnit_Framework_Constraint_IsEqual as IsEqual;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;


trait Psr7Assertions
{


    public static function assertRequestHasUri(RequestInterface $request, $uri)
    {
        Assert::assertThat($request, static::hasUri($uri));
    }


    public static function assertMessageHasHeader(MessageInterface $message, $headerName, $headerValue = null)
    {
        Assert::assertThat($message, static::hasHeader($headerName, $headerValue));
    }


    public static function assertMessageHasHeaders(MessageInterface $message, array $constraints)
    {
        Assert::assertThat($message, static::hasHeaders($constraints));
    }


    public static function assertMessageBodyMatches(MessageInterface $message, $constraint)
    {
        Assert::assertThat($message, static::bodyMatches($constraint));
    }


    public static function assertMessageBodyMatchesJson(MessageInterface $message, array $jsonConstraints)
    {
        Assert::assertThat($message, static::bodyMatchesJson($jsonConstraints));
    }


    public static function assertRequestHasMethod(RequestInterface $request, $method)
    {
        Assert::assertThat($request, static::hasMethod($method));
    }


    public static function assertRequestIsGet(RequestInterface $request)
    {
        Assert::assertThat($request, static::isGet());
    }


    public static function assertRequestIsPost(RequestInterface $request)
    {
        Assert::assertThat($request, static::isPost());
    }


    public static function assertRequestIsPut(RequestInterface $request)
    {
        Assert::assertThat($request, static::isPut());
    }


    public static function assertRequestIsDelete(RequestInterface $request)
    {
        Assert::assertThat($request, static::isDelete());
    }


    public static function hasUri($uri)
    {
        return new HasUriConstraint($uri);
    }


    public static function hasMethod($method)
    {
        return new HasMethodConstraint($method);
    }


    public static function isGet()
    {
        return static::hasMethod('GET');
    }


    public static function isPost()
    {
        return static::hasMethod('POST');
    }


    public static function isPut()
    {
        return static::hasMethod('PUT');
    }


    public static function isDelete()
    {
        return static::hasMethod('DELETE');
    }


    public static function hasHeader($name, $constraint = null)
    {
        return new HasHeaderConstraint($name, $constraint);
    }


    public static function hasHeaders(array $constraints)
    {
        $headerConstraints = [];
        foreach ($constraints as $name => $constraint) {
            $headerConstraints[] = new HasHeaderConstraint($name, $constraint);
        }

        $c = Assert::logicalAnd();
        $c->setConstraints($headerConstraints);

        return $c;
    }


    public static function hasHeaderEqualTo($name, $expected)
    {
        return new HasHeaderConstraint($name, new IsEqual($expected));
    }


    public static function bodyMatches(Constraint $constraint)
    {
        return new BodyMatchesConstraint($constraint);
    }


    public static function bodyMatchesJson(array $constraints)
    {
        return Assert::logicalAnd(
            self::hasHeaderEqualTo('content-type', 'application/json'),
            self::bodyMatches(
                Assert::logicalAnd(
                    Assert::isJson(),
                    new JsonValueMatchesMany($constraints)
                )
            )
        );
    }


}