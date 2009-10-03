<?php
	require(__DATAGEN_CLASSES__ . '/PackageGen.class.php');

	/**
	 * The Package class defined here contains any
	 * customized code for the Package class in the
	 * Object Relational Model.  It represents the "package" table 
	 * in the database, and extends from the code generated abstract PackageGen
	 * class, which contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 * 
	 * @package Qcodo Website
	 * @subpackage DataObjects
	 * 
	 */
	class Package extends PackageGen {
		/**
		 * Default "to string" handler
		 * Allows pages to _p()/echo()/print() this object, and to define the default
		 * way this object would be outputted.
		 *
		 * Can also be called directly via $objPackage->__toString().
		 *
		 * @return string a nicely formatted string representation of this object
		 */
		public function __toString() {
			return sprintf('Package Object %s',  $this->intId);
		}
		
		/**
		 * Given a string, this will create a sanitized token for it
		 * @param string $strTokenCandidate
		 * @return string
		 */
		public static function SanitizeForToken($strTokenCandidate) {
			$strTokenCandidate = trim(strtolower($strTokenCandidate));
			$intLength = strlen($strTokenCandidate);

			$strToReturn = '';

			for ($intChar = 0 ; $intChar < $intLength; $intChar++) {
				$strChar = $strTokenCandidate[$intChar];
				$intOrd = ord($strChar);

				if (($intOrd >= ord('a')) && ($intOrd <= ord('z')))
					$strToReturn .= $strChar;
				else if (($intOrd >= ord('0')) && ($intOrd <= ord('9')))
					$strToReturn .= $strChar;
				else if (($strChar == ' ') ||
						 ($strChar == '.') ||
						 ($strChar == ':') ||
						 ($strChar == '-') ||
						 ($strChar == '/') ||
						 ($strChar == '(') ||
						 ($strChar == ')') ||
						 ($strChar == '_'))
					$strToReturn .= '_';
			}

			// Cleanup leading and trailing underscores
			while (QString::FirstCharacter($strToReturn) == '_') $strToReturn = substr($strToReturn, 1);
			while (QString::LastCharacter($strToReturn) == '_') $strToReturn = substr($strToReturn, 0, strlen($strToReturn) - 1);

			// Cleanup Dupe Underscores
			while (strpos($strToReturn, '__') !== false) $strToReturn = str_replace('__', '_', $strToReturn);
			
			return $strToReturn;
		}

		/**
		 * Posts a new version of this package for a given person.
		 * @param Person $objPerson
		 * @param $strNotes
		 * @param QDateTime $dttPostDate optional, uses Now() if not specified
		 * @return PackageContribution
		 */
		public function PostContributionVersion(Person $objPerson, $strNotes, QDateTime $dttPostDate = null) {
			// Get or create PackageContribution
			$objContribution = PackageContribution::LoadByPackageIdPersonId($this->intId, $objPerson->Id);

			if (!$objContribution) {
				$objContribution = new PackageContribution();
				$objContribution->Package = $this;
				$objContribution->Person = $objPerson;
				$objContribution->Save();
			}

			$objVersion = new PackageVersion();
			$objVersion->PackageContribution = $objContribution;
			$objVersion->Notes = $strNotes;
			$objVersion->PostDate = ($dttPostDate) ? $dttPostDate : QDateTime::Now();
			$objVersion->DownloadCount = 0;
			$objVersion->VersionNumber = $objContribution->CountPackageVersions() + 1;
			$objVersion->Save();

			$objContribution->CurrentPackageVersion = $objVersion;
			$objContribution->CurrentPostDate = $objVersion->PostDate;
			$objContribution->RefreshStats();

			$this->LastPostDate = $objVersion->PostDate;
			$this->LastPostedByPerson = $objPerson;
			$this->Save();
			
			return $objContribution;
		}

		// Override or Create New Load/Count methods
		// (For obvious reasons, these methods are commented out...
		// but feel free to use these as a starting point)
/*
		public static function LoadArrayBySample($strParam1, $intParam2, $objOptionalClauses = null) {
			// This will return an array of Package objects
			return Package::QueryArray(
				QQ::AndCondition(
					QQ::Equal(QQN::Package()->Param1, $strParam1),
					QQ::GreaterThan(QQN::Package()->Param2, $intParam2)
				),
				$objOptionalClauses
			);
		}

		public static function LoadBySample($strParam1, $intParam2, $objOptionalClauses = null) {
			// This will return a single Package object
			return Package::QuerySingle(
				QQ::AndCondition(
					QQ::Equal(QQN::Package()->Param1, $strParam1),
					QQ::GreaterThan(QQN::Package()->Param2, $intParam2)
				),
				$objOptionalClauses
			);
		}

		public static function CountBySample($strParam1, $intParam2, $objOptionalClauses = null) {
			// This will return a count of Package objects
			return Package::QueryCount(
				QQ::AndCondition(
					QQ::Equal(QQN::Package()->Param1, $strParam1),
					QQ::Equal(QQN::Package()->Param2, $intParam2)
				),
				$objOptionalClauses
			);
		}

		public static function LoadArrayBySample($strParam1, $intParam2, $objOptionalClauses) {
			// Performing the load manually (instead of using Qcodo Query)

			// Get the Database Object for this Class
			$objDatabase = Package::GetDatabase();

			// Properly Escape All Input Parameters using Database->SqlVariable()
			$strParam1 = $objDatabase->SqlVariable($strParam1);
			$intParam2 = $objDatabase->SqlVariable($intParam2);

			// Setup the SQL Query
			$strQuery = sprintf('
				SELECT
					`package`.*
				FROM
					`package` AS `package`
				WHERE
					param_1 = %s AND
					param_2 < %s',
				$strParam1, $intParam2);

			// Perform the Query and Instantiate the Result
			$objDbResult = $objDatabase->Query($strQuery);
			return Package::InstantiateDbResult($objDbResult);
		}
*/




		// Override or Create New Properties and Variables
		// For performance reasons, these variables and __set and __get override methods
		// are commented out.  But if you wish to implement or override any
		// of the data generated properties, please feel free to uncomment them.
/*
		protected $strSomeNewProperty;

		public function __get($strName) {
			switch ($strName) {
				case 'SomeNewProperty': return $this->strSomeNewProperty;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		public function __set($strName, $mixValue) {
			switch ($strName) {
				case 'SomeNewProperty':
					try {
						return ($this->strSomeNewProperty = QType::Cast($mixValue, QType::String));
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				default:
					try {
						return (parent::__set($strName, $mixValue));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
*/
	}
?>