<?php
namespace Rhubarb\ResultStore;

/**
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * Copyright [2012] [Robert Allen]
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package     Rhubarb
 * @category    ResultStore
 */
use Rhubarb\Connector\Predis as PredisConnector;

/**
 * @package     Rhubarb
 * @category    ResultStore
 */
class Predis extends PredisConnector implements ResultStoreInterface
{
    /**
     * @param \Rhubarb\Task $task
     * @return bool|mixed|string
     */
    public function getTaskResult(\Rhubarb\Task $task)
    {
        $result = $this->getConnection()->get('celery-task-meta-' . $task->getId());
        if (! empty($result)) {
            $message = json_decode($result);
            if (json_last_error()) {
                throw new \Rhubarb\Exception\InvalidJsonException('Serialization Error, result is not valid JSON');
            }
            return $message;
        } else {
            return false;
        }
    }
}
