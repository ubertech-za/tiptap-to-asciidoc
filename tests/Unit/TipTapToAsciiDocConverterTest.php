<?php

declare(strict_types=1);

use UbertechZa\TipTapToAsciiDoc\TipTapToAsciiDocConverter;

describe('TipTapToAsciiDocConverter', function () {
    beforeEach(function () {
        $this->converter = new TipTapToAsciiDocConverter();
    });

    it('converts headings correctly', function () {
        $json = [
            'type' => 'doc',
            'content' => [
                [
                    'type' => 'heading',
                    'attrs' => ['level' => 1, 'textAlign' => 'start'],
                    'content' => [['type' => 'text', 'text' => 'Hello there']]
                ],
                [
                    'type' => 'heading',
                    'attrs' => ['level' => 2, 'textAlign' => 'start'],
                    'content' => [['type' => 'text', 'text' => 'This is section 1']]
                ],
                [
                    'type' => 'heading',
                    'attrs' => ['level' => 3, 'textAlign' => 'start'],
                    'content' => [['type' => 'text', 'text' => 'This is section 1.1']]
                ]
            ]
        ];

        $result = $this->converter->convert($json);

        expect($result)->toContain('= Hello there');
        expect($result)->toContain('== This is section 1');
        expect($result)->toContain('=== This is section 1.1');
    });

    it('converts paragraphs correctly', function () {
        $json = [
            'type' => 'doc',
            'content' => [
                [
                    'type' => 'paragraph',
                    'attrs' => ['textAlign' => 'start'],
                    'content' => [['type' => 'text', 'text' => 'This is a normal paragraph. An now we are going to put in some bullet points:']]
                ]
            ]
        ];

        $result = $this->converter->convert($json);

        expect($result)->toContain('This is a normal paragraph. An now we are going to put in some bullet points:');
    });

    it('converts bullet lists correctly', function () {
        $json = [
            'type' => 'doc',
            'content' => [
                [
                    'type' => 'bulletList',
                    'content' => [
                        [
                            'type' => 'listItem',
                            'content' => [[
                                'type' => 'paragraph',
                                'attrs' => ['textAlign' => 'start'],
                                'content' => [['type' => 'text', 'text' => 'Bullet 1']]
                            ]]
                        ],
                        [
                            'type' => 'listItem',
                            'content' => [[
                                'type' => 'paragraph',
                                'attrs' => ['textAlign' => 'start'],
                                'content' => [['type' => 'text', 'text' => 'Bullet 2']]
                            ]]
                        ],
                        [
                            'type' => 'listItem',
                            'content' => [[
                                'type' => 'paragraph',
                                'attrs' => ['textAlign' => 'start'],
                                'content' => [['type' => 'text', 'text' => 'Bullet 3']]
                            ]]
                        ]
                    ]
                ]
            ]
        ];

        $result = $this->converter->convert($json);

        expect($result)->toContain('* Bullet 1');
        expect($result)->toContain('* Bullet 2');
        expect($result)->toContain('* Bullet 3');
    });

    it('converts ordered lists correctly', function () {
        $json = [
            'type' => 'doc',
            'content' => [
                [
                    'type' => 'orderedList',
                    'attrs' => ['start' => 1, 'type' => null],
                    'content' => [
                        [
                            'type' => 'listItem',
                            'content' => [[
                                'type' => 'paragraph',
                                'attrs' => ['textAlign' => 'start'],
                                'content' => [['type' => 'text', 'text' => 'Number 1']]
                            ]]
                        ],
                        [
                            'type' => 'listItem',
                            'content' => [[
                                'type' => 'paragraph',
                                'attrs' => ['textAlign' => 'start'],
                                'content' => [['type' => 'text', 'text' => 'Number 2']]
                            ]]
                        ],
                        [
                            'type' => 'listItem',
                            'content' => [[
                                'type' => 'paragraph',
                                'attrs' => ['textAlign' => 'start'],
                                'content' => [['type' => 'text', 'text' => 'Number 3']]
                            ]]
                        ]
                    ]
                ]
            ]
        ];

        $result = $this->converter->convert($json);

        expect($result)->toContain('. Number 1');
        expect($result)->toContain('. Number 2');
        expect($result)->toContain('. Number 3');
    });

    it('converts blockquotes correctly', function () {
        $json = [
            'type' => 'doc',
            'content' => [
                [
                    'type' => 'blockquote',
                    'content' => [[
                        'type' => 'paragraph',
                        'attrs' => ['textAlign' => 'start'],
                        'content' => [['type' => 'text', 'text' => 'This is a bubble. Blah blah blah']]
                    ]]
                ]
            ]
        ];

        $result = $this->converter->convert($json);

        expect($result)->toContain('[quote]');
        expect($result)->toContain('____');
        expect($result)->toContain('This is a bubble. Blah blah blah');
    });

    it('converts links correctly', function () {
        $json = [
            'type' => 'doc',
            'content' => [
                [
                    'type' => 'paragraph',
                    'attrs' => ['textAlign' => 'start'],
                    'content' => [
                        ['type' => 'text', 'text' => 'This is a link to '],
                        [
                            'type' => 'text',
                            'marks' => [[
                                'type' => 'link',
                                'attrs' => [
                                    'href' => 'https://www.google.com',
                                    'target' => '_blank',
                                    'rel' => 'noopener noreferrer nofollow',
                                    'class' => null
                                ]
                            ]],
                            'text' => 'google'
                        ],
                        ['type' => 'text', 'text' => '!']
                    ]
                ]
            ]
        ];

        $result = $this->converter->convert($json);

        expect($result)->toContain('This is a link to https://www.google.com[google]!');
    });

    it('converts simple tables correctly', function () {
        $json = [
            'type' => 'doc',
            'content' => [
                [
                    'type' => 'table',
                    'content' => [
                        [
                            'type' => 'tableRow',
                            'content' => [
                                [
                                    'type' => 'tableHeader',
                                    'attrs' => ['colspan' => 1, 'rowspan' => 1, 'colwidth' => null],
                                    'content' => [[
                                        'type' => 'paragraph',
                                        'attrs' => ['textAlign' => 'start'],
                                        'content' => [['type' => 'text', 'text' => 'Table Heading 1']]
                                    ]]
                                ],
                                [
                                    'type' => 'tableHeader',
                                    'attrs' => ['colspan' => 1, 'rowspan' => 1, 'colwidth' => null],
                                    'content' => [[
                                        'type' => 'paragraph',
                                        'attrs' => ['textAlign' => 'start'],
                                        'content' => [['type' => 'text', 'text' => 'Table Heading 2']]
                                    ]]
                                ]
                            ]
                        ],
                        [
                            'type' => 'tableRow',
                            'content' => [
                                [
                                    'type' => 'tableCell',
                                    'attrs' => ['colspan' => 1, 'rowspan' => 1, 'colwidth' => null],
                                    'content' => [[
                                        'type' => 'paragraph',
                                        'attrs' => ['textAlign' => 'start'],
                                        'content' => [['type' => 'text', 'text' => 'Row1, Col 1']]
                                    ]]
                                ],
                                [
                                    'type' => 'tableCell',
                                    'attrs' => ['colspan' => 1, 'rowspan' => 1, 'colwidth' => null],
                                    'content' => [[
                                        'type' => 'paragraph',
                                        'attrs' => ['textAlign' => 'start'],
                                        'content' => [['type' => 'text', 'text' => 'Row 1, Col 2']]
                                    ]]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $result = $this->converter->convert($json);

        expect($result)->toContain('|===');
        expect($result)->toContain('| Table Heading 1');
        expect($result)->toContain('| Table Heading 2');
        expect($result)->toContain('| Row1, Col 1');
        expect($result)->toContain('| Row 1, Col 2');
        
        // Check that rows are separated by blank lines
        expect($result)->toMatch('/\|\s*Table Heading 2\s*\n\s*\n\|\s*Row1, Col 1/');
    });

    it('converts tables with lists in cells correctly', function () {
        $json = [
            'type' => 'doc',
            'content' => [
                [
                    'type' => 'table',
                    'content' => [
                        [
                            'type' => 'tableRow',
                            'content' => [
                                [
                                    'type' => 'tableCell',
                                    'attrs' => ['colspan' => 1, 'rowspan' => 1, 'colwidth' => null],
                                    'content' => [
                                        [
                                            'type' => 'bulletList',
                                            'content' => [
                                                [
                                                    'type' => 'listItem',
                                                    'content' => [[
                                                        'type' => 'paragraph',
                                                        'attrs' => ['textAlign' => 'start'],
                                                        'content' => [['type' => 'text', 'text' => 'Bullets inside']]
                                                    ]]
                                                ],
                                                [
                                                    'type' => 'listItem',
                                                    'content' => [[
                                                        'type' => 'paragraph',
                                                        'attrs' => ['textAlign' => 'start'],
                                                        'content' => [['type' => 'text', 'text' => 'Bullets inside']]
                                                    ]]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $result = $this->converter->convert($json);

        expect($result)->toContain('a|');
        expect($result)->toContain('* Bullets inside');
        // Check that the first bullet is on a new line after a|
        expect($result)->toMatch('/a\|\s*\n\*\s*Bullets inside/');
    });

    it('converts tables with merged cells correctly', function () {
        $json = [
            'type' => 'doc',
            'content' => [
                [
                    'type' => 'table',
                    'content' => [
                        [
                            'type' => 'tableRow',
                            'content' => [
                                [
                                    'type' => 'tableCell',
                                    'attrs' => ['colspan' => 2, 'rowspan' => 1, 'colwidth' => null],
                                    'content' => [[
                                        'type' => 'paragraph',
                                        'attrs' => ['textAlign' => 'start'],
                                        'content' => [['type' => 'text', 'text' => 'Merged']]
                                    ]]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $result = $this->converter->convert($json);

        expect($result)->toContain('2+| Merged');
    });

    it('converts complete complex document correctly', function () {
        // Use the exact JSON structure you provided
        $json = [
            'type' => 'doc',
            'content' => [
                [
                    'type' => 'heading',
                    'content' => [['type' => 'text', 'text' => 'Hello there']],
                    'attrs' => ['textAlign' => 'start', 'level' => 1]
                ],
                [
                    'type' => 'heading',
                    'attrs' => ['textAlign' => 'start', 'level' => 2],
                    'content' => [['type' => 'text', 'text' => 'This is section 1']]
                ],
                [
                    'type' => 'bulletList',
                    'content' => [
                        [
                            'type' => 'listItem',
                            'content' => [[
                                'type' => 'paragraph',
                                'attrs' => ['textAlign' => 'start'],
                                'content' => [['type' => 'text', 'text' => 'Bullet 1']]
                            ]]
                        ]
                    ]
                ],
                [
                    'type' => 'orderedList',
                    'attrs' => ['start' => 1, 'type' => null],
                    'content' => [
                        [
                            'type' => 'listItem',
                            'content' => [[
                                'type' => 'paragraph',
                                'attrs' => ['textAlign' => 'start'],
                                'content' => [['type' => 'text', 'text' => 'Number 1']]
                            ]]
                        ]
                    ]
                ],
                [
                    'type' => 'table',
                    'content' => [
                        [
                            'type' => 'tableRow',
                            'content' => [
                                [
                                    'type' => 'tableCell',
                                    'attrs' => ['colspan' => 1, 'rowspan' => 1, 'colwidth' => null],
                                    'content' => [
                                        [
                                            'type' => 'bulletList',
                                            'content' => [
                                                [
                                                    'type' => 'listItem',
                                                    'content' => [[
                                                        'type' => 'paragraph',
                                                        'attrs' => ['textAlign' => 'start'],
                                                        'content' => [['type' => 'text', 'text' => 'Bullets inside']]
                                                    ]]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'type' => 'tableCell',
                                    'attrs' => ['colspan' => 2, 'rowspan' => 1, 'colwidth' => null],
                                    'content' => [[
                                        'type' => 'paragraph',
                                        'attrs' => ['textAlign' => 'start'],
                                        'content' => [['type' => 'text', 'text' => 'Merged']]
                                    ]]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $result = $this->converter->convert($json);

        // Test all major elements are present
        expect($result)->toContain('= Hello there');
        expect($result)->toContain('== This is section 1');
        expect($result)->toContain('* Bullet 1');
        expect($result)->toContain('. Number 1');
        expect($result)->toContain('|===');
        expect($result)->toContain('a|');
        expect($result)->toContain('2+| Merged');
        expect($result)->toContain('* Bullets inside');
    });

    it('handles empty paragraphs gracefully', function () {
        $json = [
            'type' => 'doc',
            'content' => [
                [
                    'type' => 'paragraph',
                    'attrs' => ['textAlign' => 'start']
                ]
            ]
        ];

        $result = $this->converter->convert($json);

        // Should not crash and should return clean result
        expect($result)->toBeString();
    });

    it('handles text formatting marks correctly', function () {
        $json = [
            'type' => 'doc',
            'content' => [
                [
                    'type' => 'paragraph',
                    'content' => [
                        [
                            'type' => 'text',
                            'marks' => [['type' => 'bold']],
                            'text' => 'Bold text'
                        ],
                        ['type' => 'text', 'text' => ' and '],
                        [
                            'type' => 'text',
                            'marks' => [['type' => 'italic']],
                            'text' => 'italic text'
                        ],
                        ['type' => 'text', 'text' => ' and '],
                        [
                            'type' => 'text',
                            'marks' => [['type' => 'code']],
                            'text' => 'code text'
                        ]
                    ]
                ]
            ]
        ];

        $result = $this->converter->convert($json);

        expect($result)->toContain('*Bold text*');
        expect($result)->toContain('_italic text_');
        expect($result)->toContain('`code text`');
    });
});