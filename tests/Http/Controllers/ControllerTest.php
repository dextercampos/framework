<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Framework\Http\Controllers;

use EoneoPay\ApiFormats\Interfaces\FormattedApiResponseInterface;
use EoneoPay\Externals\Bridge\Laravel\Request;
use EoneoPay\Framework\Exceptions\EntityNotFoundException;
use Illuminate\Http\Request as HttpRequest;
use Tests\EoneoPay\Framework\Database\Stubs\EntityStub;
use Tests\EoneoPay\Framework\Exceptions\Stubs\CustomNotFoundExceptionStub;
use Tests\EoneoPay\Framework\Http\Stubs\ControllerStub;
use Tests\EoneoPay\Framework\TestCases\WithEntityManagerTestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects) Due to flexibility of the base controller
 */
class ControllerTest extends WithEntityManagerTestCase
{
    /**
     * Test controller create entity and return formatted api response.
     *
     * @return void
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Doctrine\ORM\ORMException
     * @throws \EoneoPay\Externals\ORM\Exceptions\EntityValidationFailedException
     * @throws \EoneoPay\Externals\ORM\Exceptions\ORMException
     */
    public function testCreateEntity(): void
    {
        $create = (new ControllerStub($this->getEntityManager()))
            ->createEntityAndRespond(EntityStub::class, new Request(new HttpRequest()));

        /** @noinspection UnnecessaryAssertionInspection Ensure correct class is returned */
        self::assertInstanceOf(FormattedApiResponseInterface::class, $create);
        self::assertInstanceOf(EntityStub::class, $create->getContent());
        self::assertEquals(201, $create->getStatusCode());
    }

    /**
     * Test controller remove entity and return formatted api response.
     *
     * @return void
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Doctrine\ORM\ORMException
     * @throws \EoneoPay\Externals\ORM\Exceptions\EntityValidationFailedException
     * @throws \EoneoPay\Externals\ORM\Exceptions\ORMException
     * @throws \EoneoPay\Utils\Exceptions\NotFoundException
     */
    public function testRemoveEntity(): void
    {
        $entity = new EntityStub();
        $controller = new ControllerStub($this->getEntityManager());

        $controller->saveEntity($entity);
        $remove = $controller->deleteEntityAndRespond(EntityStub::class, (string)$entity->getEntityId());

        /** @noinspection UnnecessaryAssertionInspection Ensure correct class is returned */
        self::assertInstanceOf(FormattedApiResponseInterface::class, $remove);
        self::assertEquals([], $remove->getContent());
        self::assertEquals(203, $remove->getStatusCode());
    }

    /**
     * Test controller throw custom not found exception when entity does not exist.
     *
     * @return void
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Doctrine\ORM\ORMException
     * @throws \EoneoPay\Utils\Exceptions\NotFoundException
     */
    public function testRetrieveEntityWithCustomNotFoundException(): void
    {
        $this->expectException(CustomNotFoundExceptionStub::class);

        (new ControllerStub($this->getEntityManager()))->retrieveEntity(
            EntityStub::class,
            'invalid',
            CustomNotFoundExceptionStub::class
        );
    }

    /**
     * Test controller throw not found exception when entity does not exist.
     *
     * @return void
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Doctrine\ORM\ORMException
     * @throws \EoneoPay\Utils\Exceptions\NotFoundException
     */
    public function testRetrieveEntityWithNotFoundException(): void
    {
        $this->expectException(EntityNotFoundException::class);

        (new ControllerStub($this->getEntityManager()))->retrieveEntity(EntityStub::class, 'invalid');
    }

    /**
     * Test controller update entity and return formatted api response.
     *
     * @return void
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Doctrine\ORM\ORMException
     * @throws \EoneoPay\Externals\ORM\Exceptions\EntityValidationFailedException
     * @throws \EoneoPay\Externals\ORM\Exceptions\ORMException
     * @throws \EoneoPay\Utils\Exceptions\NotFoundException
     */
    public function testUpdateEntity(): void
    {
        $entity = new EntityStub();
        $controller = new ControllerStub($this->getEntityManager());

        $controller->saveEntity($entity);
        $update = $controller->updateEntityAndRespond(
            EntityStub::class,
            (string)$entity->getEntityId(),
            new Request(new HttpRequest())
        );

        /** @noinspection UnnecessaryAssertionInspection Ensure correct class is returned */
        self::assertInstanceOf(FormattedApiResponseInterface::class, $update);
        self::assertInstanceOf(EntityStub::class, $update->getContent());
        self::assertEquals(200, $update->getStatusCode());
    }
}
