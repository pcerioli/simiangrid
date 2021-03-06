<?php
/** Simian grid services
 *
 * PHP version 5
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. The name of the author may not be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 * THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *
 * @package    SimianGrid
 * @author     Jim Radford <http://www.jimradford.com/>
 * @copyright  Open Metaverse Foundation
 * @license    http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 * @link       http://openmetaverse.googlecode.com/
 */
require_once(COMMONPATH . 'ALT.php');

function output_results($nodes)
{
    header("Content-Type: application/json", true);
    echo '{ "Success": true, "Items": [';
    
    $count = count($nodes);
    for ($i = 0; $i < $count; $i++)
    {
        echo $nodes[$i]->toOSD();
        if ($i < $count - 1)
            echo ',';
    }
    
    echo '] }';
}

class GetInventoryNode implements IGridService
{
    private $inventory;

    public function Execute($db, $params)
    {
        $itemID = NULL;
        $ownerID = NULL;
        $fetchFolders = TRUE;
        $fetchItems = TRUE;
        $childrenOnly = TRUE;
        
        if (!isset($params["ItemID"], $params["OwnerID"]) || !UUID::TryParse($params["ItemID"], $itemID) || !UUID::TryParse($params["OwnerID"], $ownerID))
        {
            header("Content-Type: application/json", true);
            echo '{ "Message": "Invalid parameters" }';
            exit();
        }
        
        if (isset($params["IncludeFolders"]))
            $fetchFolders = (bool)$params["IncludeFolders"];
        if (isset($params["IncludeItems"]))
            $fetchItems = (bool)$params["IncludeItems"];
        if (isset($params["ChildrenOnly"]))
            $childrenOnly = (bool)$params["ChildrenOnly"];
        
        $this->inventory = new ALT($db);
        
        // Optimization for inventory skeleton fetching
        if ($itemID == $ownerID && $fetchFolders && !$fetchItems && !$childrenOnly)
        {
            log_message('debug', 'Doing a FetchSkeleton for ' . $ownerID);
            
            if ($library = $this->inventory->FetchSkeleton($ownerID))
            {
                output_results($library);
                exit();
            }
            else
            {
                header("Content-Type: application/json", true);
                echo '{ "Message": "Inventory not found" }';
                exit();
            }
        }
        else
        {
            if ($nodes = $this->inventory->FetchDescendants($itemID, $fetchFolders, $fetchItems, $childrenOnly))
            {
                output_results($nodes);
                exit();
            }
            else
            {
                header("Content-Type: application/json", true);
                echo '{ "Message": "Item or folder not found" }';
                exit();
            }
        }
    }
}
