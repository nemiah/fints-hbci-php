<?php /** @noinspection PhpUnused */

namespace Fhp\Segment\CAZ;

use Fhp\DataTypes\Bin;
use Fhp\Segment\BaseSegment;

/**
 * Segment: Kontoumsätze rückmelden/Zeitraum camt
 * Bezugssegment: HKCAZ
 * Sender: Kreditinstitut
 *
 * @link: https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Messages_Geschaeftsvorfaelle_2015-08-07_final_version.pdf
 * Page: 71
 */
class HICAZv1 extends BaseSegment
{
    /**
     * Kontoverbindung international
     * @var \Fhp\Segment\Common\Kti
     */
    public $kontoverbindungInternational;

    /**
     * Der camt-Descriptor beschreibt Ort, Name und Version einer camt Schema-Definition als URN.
     * @var string
     */
    public $camtDescriptor;

    /**
     * Umsätze, die auf dem Kundenkonto erfolgt sind und zum Zeitpunkt des Kundenauftrags vom Kreditinstitut bereits
     * gebucht wurden.
     * Gebuchte camt-Umsätze werden als camt.052 message für Umsatzabfragen bzw. camt.053 message für den elektronischen
     * Kontoauszug (s. [Datenformate]) bereitgestellt und werden als transparentes Datenformat im Sinne von FinTS transportiert
     *
     * @var Bin
     */
    public $gebuchteUmsaetze;

    /**
     * Noch nicht gebuchte Umsätze, die dem Kunden im camt.052-Format zusätzlich rückgemeldet werden und zum Zeitpunkt
     * des Kundenauftrags vom Kreditinstitut noch nicht gebucht wurden. Nicht gebuchte Umsätze können nicht auftreten,
     * wenn der vom Kunden angegebene Zeitraum in der Vergangenheit liegt.
     *
     * @var Bin|null
     */
    public $nichtGebuchteUmsaetze;

    /**
     * @return \Fhp\Segment\Common\Kti
     */
    public function getKontoverbindungInternational(): \Fhp\Segment\Common\Kti
    {
        return $this->kontoverbindungInternational;
    }

    /**
     * @return string
     */
    public function getCamtDescriptor(): string
    {
        return $this->camtDescriptor;
    }

    /**
     * @return Bin
     */
    public function getGebuchteUmsaetze(): Bin
    {
        return $this->gebuchteUmsaetze;
    }

    /**
     * @return Bin|null
     */
    public function getNichtGebuchteUmsaetze(): ?Bin
    {
        return $this->nichtGebuchteUmsaetze;
    }
}
