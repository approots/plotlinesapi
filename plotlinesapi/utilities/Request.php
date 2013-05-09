<?php
namespace utilities;

class Request
{
    public static function objectFromJson($request, $obj = null)
    {
        $requestData = $request->getBody();

        if ($requestData) {
            $requestData = json_decode($requestData);

            if ($requestData)
            {
                if ($obj)
                {
                    // foreach $obj property, overwrite with value from $post if defined
                    foreach ($obj as $key => $val)
                    {
                        if (isset($requestData->$key))
                        {
                            $obj->$key = $requestData->$key;
                        }
                    }

                    $requestData = $obj;
                }
            }
        }

        return $requestData;
    }
}