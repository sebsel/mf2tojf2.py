<?php
# -*- coding: utf-8 -*-
# mf2 to jf2 converter
# licence cc0
#  2015 Kevin Marks (python)
#  2016 Sebastiaan Andeweg (php)



function flattenProperties($items, $isOuter = false) {
  if (is_array($items)) {
    if (count($items) < 1) return [];
    if (count($items) == 1) {
      $item = $items[0];
      if (is_array($item)) {
        if (key_exists('type', $item)) {
          $props = [
            'type' => isset($item['type'][0]) ? substr($item['type'][0],2) : ''
          ];

          $properties = isset($item['properties']) ? $item['properties'] : [];

          foreach ($properties as $k => $prop) {
            $props[$k] = flattenProperties($prop);
          }

          $children = isset($item['children']) ? $item['children'] : [];

          if ($children) {
            if (count($children) == 1) {
              $props['children'] = [flattenProperties($children)];

            } else {
              $props['children'] = flattenProperties($children);
            }
          }
          return $props;

        } elseif (key_exists('value', $item)) {
          return $item['value'];
        } else {
          return '';
        }
      } else {
        return $item;
      }


    } elseif ($isOuter) {
      $children = [];
      foreach ($items as $child) {
        $children[] = flattenProperties([$child]);
      }
      return ["children" => $children];

    } else {
      $children = [];
      foreach ($items as $child) {
        $children[] = flattenProperties([$child]);
      }
      return $children;
    }
  } else {
    return $items; // not a list, so string
  }
}

function mf2tojf2($mf2) {
  $items = isset($mf2['items']) ? $mf2['items'] : [];
  $jf2 = flattenProperties($items, true);
  // echo $jf2;
  return $jf2;
}