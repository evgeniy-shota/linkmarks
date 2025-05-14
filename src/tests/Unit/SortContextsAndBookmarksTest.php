<?php

namespace Tests\Unit;

use App\Actions\SortContextsAndBookmarks;
use PHPUnit\Framework\TestCase;
use function Laravel\Prompts\note;

class SortContextsAndBookmarksTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_mix_contexts_and_bookmarks(): void
    {
        $contexts = [];
        $bookmarks = [];
        $sampleResult = [];

        for (
            $i = 0, $size = 20, $csOrderValue = 1, $bsOrderValue = 0;
            $i < $size;
            $i++, $csOrderValue += 4, $bsOrderValue += 2
        ) {
            $contexts[$i]['order'] = $csOrderValue;
            $bookmarks[$i]['order'] = $bsOrderValue;
            $sampleResult[] = $csOrderValue;
            $sampleResult[] = $bsOrderValue;
        }

        sort($sampleResult);

        $startTime = hrtime(true);
        $result = SortContextsAndBookmarks::mixInOrder($contexts, $bookmarks);
        $endTime = hrtime(true);
        note('sorted: ' .  ($endTime - $startTime) / 1e+6 . 'ms');
        $this->assertTrue($sampleResult === array_column($result, 'order'));
    }

    public function test_mix_contexts_and_empty_bookmarks(): void
    {
        $contexts = [];
        $bookmarks = [];

        for (
            $i = 0, $size = 20, $csOrderValue = 1;
            $i < $size;
            $i++, $csOrderValue += 2
        ) {
            $contexts[$i]['order'] = $csOrderValue;
        }

        $startTime = hrtime(true);
        $result = SortContextsAndBookmarks::mixInOrder($contexts, $bookmarks);
        $endTime = hrtime(true);
        note('sorted: ' .  ($endTime - $startTime) / 1e+6 . 'ms');
        $this->assertTrue($contexts === $result);
    }

    public function test_mix_empty_contexts_and_bookmarks(): void
    {
        $contexts = [];
        $bookmarks = [];

        for (
            $i = 0, $size = 20, $bsOrderValue = 0;
            $i < $size;
            $i++, $bsOrderValue += 2
        ) {
            $bookmarks[$i]['order'] = $bsOrderValue;
        }

        $startTime = hrtime(true);
        $result = SortContextsAndBookmarks::mixInOrder($contexts, $bookmarks);
        $endTime = hrtime(true);
        note('sorted: ' .  ($endTime - $startTime) / 1e+6 . 'ms');
        $this->assertTrue($bookmarks === $result, 'order');
    }

    public function test_mix_empty_contexts_and_empty_bookmarks(): void
    {
        $contexts = [];
        $bookmarks = [];

        $startTime = hrtime(true);
        $result = SortContextsAndBookmarks::mixInOrder($contexts, $bookmarks);
        $endTime = hrtime(true);
        note('sorted: ' .  ($endTime - $startTime) / 1e+6 . 'ms');
        $this->assertTrue(count($result) === 0);
    }

    public function test_mix_contexts_and_one_bookmark(): void
    {
        $contexts = [
            ['order' => 1],
            ['order' => 2],
            ['order' => 3],
            ['order' => 4],
            ['order' => 5],
            ['order' => 6],
            ['order' => 7],
            ['order' => 8],
        ];
        $bookmarks = [
            ['order' => 9]
        ];

        $sampleResult = array_merge($contexts, $bookmarks);

        $startTime = hrtime(true);
        $result = SortContextsAndBookmarks::mixInOrder($contexts, $bookmarks);
        $endTime = hrtime(true);

        note('sorted: ' .  ($endTime - $startTime) / 1e+6 . 'ms');
        $this->assertTrue($sampleResult === $result);
    }
}
