<?php

namespace Fhp\Segment;

use Fhp\Syntax\Delimiter;
use Fhp\Syntax\Parser;
use Fhp\Syntax\Serializer;

/**
 * Class BaseSegment
 *
 * Base class for segments. Sub-classes names need to follow the format "<Kennung>v<Version>" where <Kennung> is the
 * type of the segment (e.g. "HITANS") and <Version> is the numeric version. The *public* member fields of a sub-class
 * determine the structure of the segment. The order matters for the wire format, whereas the field names are only used
 * for documentation/readability purposes within this library. See {@link HITANSv1} for an example of a sub-class.
 *
 * @package Fhp\Segment
 */
abstract class BaseSegment implements SegmentInterface
{
    /** @var string Name of the PHP namespace under which all the segments are stored. */
    const SEGMENT_NAMESPACE = 'Fhp\Segment';

    /**
     * Reference to the descriptor for this type of segment.
     * @var SegmentDescriptor
     */
    private $descriptor;

    /**
     * @var Segmentkopf
     */
    public $segmentkopf;

    public function __construct()
    {
        $this->descriptor = SegmentDescriptor::get(static::class);
    }

    public function getDescriptor()
    {
        return $this->descriptor;
    }

    public function getName()
    {
        return $this->descriptor->kennung;
    }

    /**
     * @throws \InvalidArgumentException If any element in this segment is invalid.
     */
    public function validate()
    {
        $this->descriptor->validateObject($this);
    }

    /**
     * Short-hand for {@link Serializer#serializeSegment()}.
     * @return string The HBCI wire format representation of this segment, terminated by the segment delimiter.
     */
    public function serialize()
    {
        return Serializer::serializeSegment($this);
    }

    // TODO Consider removing this along with SegmentInterface in future.
    public function __toString()
    {
        return $this->serialize();
    }

    /**
     * Convenience function for {@link Parser#parseSegment()}.
     * @param string $rawSegment The serialized wire format for a single segment (segment delimiter may be present at
     *     the end, or not).
     * @return BaseSegment The parsed segment.
     */
    public static function parse($rawSegment)
    {
        if (static::class === BaseSegment::class) {
            // Called as BaseSegment::parse(), so we need to determine the right segment type/class.
            $firstElementDelimiter = strpos($rawSegment, Delimiter::ELEMENT);
            if ($firstElementDelimiter === false) {
                throw new \InvalidArgumentException("Invalid segment $rawSegment");
            }
            /** @var Segmentkopf $segmentkopf */
            $segmentkopf = Segmentkopf::parse(substr($rawSegment, 0, $firstElementDelimiter));
            $segmentType = static::SEGMENT_NAMESPACE . '\\' . $segmentkopf->segmentkennung . '\\'
                . $segmentkopf->segmentkennung . 'v' . $segmentkopf->segmentversion;
            if (!class_exists($segmentType)) {
                throw new \InvalidArgumentException("Unsupported segment type/version $segmentType");
            }
        } else {
            // The parse() function was called on the segment subclass itself.
            $segmentType = static::class;
        }
        return Parser::parseSegment($rawSegment, $segmentType);
    }

}
