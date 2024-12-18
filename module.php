<?php
/*
Family Tree Home Page
Copyright (C) 2024 by Robert Chapin

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/
declare(strict_types=1);

namespace Miqrogroove\Webtrees\FamilyTreeHome;

use Fisharebest\Webtrees\Http\RequestHandlers\HomePage;
use Fisharebest\Webtrees\Http\RequestHandlers\LoginPage;
use Fisharebest\Webtrees\Module\AbstractModule;
use Fisharebest\Webtrees\Module\ModuleCustomInterface;
use Fisharebest\Webtrees\Module\ModuleCustomTrait;
use Fisharebest\Webtrees\Registry;
use Fisharebest\Webtrees\Validator;
use Fisharebest\Webtrees\Webtrees;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

require_once __DIR__ . '/TreeHomePage.php';

return new class extends AbstractModule implements ModuleCustomInterface, MiddlewareInterface {
    use ModuleCustomTrait;

    public function boot(): void
    {
        $this->rebind(HomePage::class, TreeHomePage::class);
    }

    /**
     * Dependency injection binding for webtrees
     *
     * @param string $base   Also known as the "abstract" class.
     * @param string $custom Also known as the "concrete" class.
     */
    private function rebind(string $base, string $custom): void
    {
        if (version_compare(Webtrees::VERSION, '2.2.0', '>=')) {
            $objects = Registry::container();
            $objects->set($base, $objects->get($custom));
        } else {
            $objects = app();
            $objects->bind($base, $custom);
        }
    }

    /**
     * For any site using the original LoginPage handler,
     * we need to treat any ?url=.../my-page as invalid
     * to prevent explicit redirection requests.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getAttribute('route')->name === LoginPage::class) {
            $params = $request->getQueryParams();
            $url    = $params['url'] ?? '';
            if (Validator::attributes($request)->boolean('rewrite_urls', $default = false)) {
                $end = '/my-page';
            } else {
                $end = '%2Fmy-page';
            }
            if (substr_compare($url, $end, -strlen($end)) === 0) {
                $params['url'] = substr($url, 0, -strlen($end));
                $request       = $request->withQueryParams($params);
            }
        }

        return $handler->handle($request);
    }

    /**
     * How should this module be identified in the control panel, etc.?
     *
     * @return string
     */
    public function title(): string
    {
        return 'Family Tree Home Page';
    }

    /**
     * A sentence describing what this module does.
     *
     * @return string
     */
    public function description(): string
    {
        return 'This module changes the front page for signed-in users. After a sign in from the front page, users will go back to the Family Tree instead of redirecting to My Page.';
    }

    /**
     * The person or organisation who created this module.
     *
     * @return string
     */
    public function customModuleAuthorName(): string
    {
        return 'Robert Chapin';
    }

    /**
     * The version of this module.
     *
     * @return string
     */
    public function customModuleVersion(): string
    {
        return '1.0.05';
    }

    /**
     * Where to get support for this module.  Perhaps a github repository?
     *
     * @return string
     */
    public function customModuleSupportUrl(): string
    {
        return 'https://github.com/miqrogroove/family-tree-home';
    }

};
