<?php
namespace App\Tests\Infrastructure\Security;

use App\Infrastructure\Exception\ApiException;
use App\Infrastructure\Security\TokenValidator;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

#[Group('unit')]
#[Group('security')]
final class TokenValidatorTest extends TestCase
{
    public function testMustValidateTokenAndSetEmailClaimOnRequest(): void
    {
        $this->mockProvider(expectedStatus: 200);
    }

    public function testMustReturnAnErrorIfInvalidTokenProvided(): void
    {
        $this->expectException(ApiException::class);
        $this->mockProvider(expectedStatus: 401);
    }

    public function testMustReturnAnErrorIfEmailClaimMissing(): void
    {
        $this->mockProvider(expectedStatus: 200, filledEmail: false);
    }

    public function testMustReturnAnErrorIfAuthorizationHeaderMissing(): void
    {
        $this->expectException(ApiException::class);
        $this->mockProvider(expectedStatus: 401, bearer: false, email: false, apiCall: false, assertion: false);
    }

    private function mockProvider(int $expectedStatus, bool $bearer = true, bool $email = true, bool $filledEmail = true, bool $apiCall = true, bool $assertion = true): void
    {
        $emailPlaceholder = $filledEmail ? 'test@example.com' : '';

        $clientMock = $this->createMock(HttpClientInterface::class);
        $paramsMock = $this->createMock(ParameterBagInterface::class);
        $request    = new Request();

        if ($bearer) {
            $request->headers->set('Authorization', 'Bearer faketoken');
        }

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn($expectedStatus);

        if ($email) {
            $responseMock->method('toArray')->willReturn(['email' => $emailPlaceholder]);
        }

        if ($apiCall) {
            $clientMock->method('request')->willReturn($responseMock);
            $paramsMock->method('get')->willReturn('http://fake-auth-service');
        }

        $validator = new TokenValidator($clientMock, $paramsMock);
        $validator->validate($request);

        if ($assertion) {
            $this->assertSame($emailPlaceholder, $request->attributes->get('email'));
        }
    }
}
