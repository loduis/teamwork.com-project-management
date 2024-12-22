<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use RuntimeException;

use TeamWorkPm\Rest\Client as HttpClient;

/**
 * @method static Category\File categoryFile()
 * @method static Category\Link categoryLink()
 * @method static Category\Message categoryMessage()
 * @method static Category\Notebook categoryNotebook()
 * @method static Category\Project categoryProject()
 * @method static Comment\File commentFile()
 * @method static Comment\Milestone commentMilestone()
 * @method static Comment\Notebook commentNotebook()
 * @method static Comment\Task commentTask()
 * @method static Comment\Link commentLink()
 * @method static Me\Status meStatus()
 * @method static Message\Reply messageReply()
 * @method static People\Status peopleStatus()
 * @method static Portfolio\Board portfolioBoard()
 * @method static Portfolio\Card portfolioCard()
 * @method static Portfolio\Column portfolioColumn()
 * @method static Project\People projectPeople()
 * @method static Project\Rate projectRate()
 * @method static Project\File projectFile()
 * @method static Project\CustomField projectCustomField()
 * @method static Task\CustomField taskCustomField()
 * @method static Task\File taskFile()
 * @method static Account account()
 * @method static CustomField customField()
 * @method static Activity activity()
 * @method static Auth auth()
 * @method static Company company()
 * @method static Link link()
 * @method static Message message()
 * @method static Milestone milestone()
 * @method static Notebook notebook()
 * @method static People people()
 * @method static Project project()
 * @method static Role role()
 * @method static Tag tag()
 * @method static Task task()
 * @method static TaskList taskList()
 * @method static Time time()
 * @method static File file()
 */
class Factory
{
    private static array $instances = [];

    /**
     * @param string $className
     * @return Model
     */
    public static function build(string $className, ?HttpClient $httpClient = null)
    {
        [$className, $url, $key] = static::resolve($className);

        $clientHash = md5($url . '-' . $key);

        if ($httpClient !== null) {
            $oldClient = static::$instances[$clientHash] ?? null;
            if ($oldClient !== null && $httpClient !== $oldClient) {
                static::$instances = [];
            }
            static::$instances[$clientHash] = $httpClient;
        } elseif (($httpClient = (static::$instances[$clientHash] ?? null)) === null) {
            $httpClient = static::$instances[$clientHash] = new HttpClient($url, $key);
        }

        $classHash = md5($className . '-' . $url);

        return static::$instances[$classHash] ?? (
            static::$instances[$classHash] = new $className($httpClient)
        );
    }

    /**
     *
     * @param string $url
     * @param string $key
     * @param HttpClient $httpClient
     * @return void
     */
    /*
    public static function shareHttpClient(string $url, string $key, HttpClient $httpClient): void
    {
        $hash = md5($url . '-' . $key);
        static::$instances[$hash] = $httpClient;
    }
    */

    public static function resolve(string $className)
    {
        $className = str_replace(['/', '.'], '\\', $className);
        $className = preg_replace_callback('/(\\\.)/', function ($matches) {
            return strtoupper($matches[1]);
        }, $className);
        $className = ucfirst($className);
        if (strcasecmp($className, 'task\\list') === 0) {
            $className = 'Task_List';
        }
        $className = '\\' . __NAMESPACE__ . '\\' . $className;

        if (!class_exists($className)) {
            throw new RuntimeException("The class '$className' no exists");
        }

        return [$className, ...Auth::get()];
    }

    public static function __callStatic(string $className, array $arguments)
    {
        $className = preg_replace('/([a-z])([A-Z])/', '$1.$2', $className);

        return static::build($className);
    }
}
