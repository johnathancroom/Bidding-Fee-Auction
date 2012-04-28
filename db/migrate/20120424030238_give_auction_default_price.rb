class GiveAuctionDefaultPrice < ActiveRecord::Migration
  def up
    change_column :auctions, :price, :integer, :default => 0
  end

  def down
    # You can't currently remove default values in Rails
    raise ActiveRecord::IrreversibleMigration, "Can't remove the default"
  end
end
